# run with `ruby app.rb`

require 'rubygems'
require 'sinatra'
require 'activerecord'
require 'geokit'
require 'yahoo-weather'

# FIXME: why doesn't the gem work?
# require 'google-geo'
require 'lib/google/geo'


include Geokit::Geocoders
Geokit::default_units = :kilometers


API_KEY = 'ABQIAAAAKkxF5_xalx0zhXRdE2dXBhT2yXp_ZAY8_ufC3CFXhHIE1NvwkxSjTiBzhT9UNDbF96KLnxX3-7jrGg'



configure do
  ActiveRecord::Base.establish_connection(
    :adapter => 'sqlite3',
    :dbfile =>  'db/app.sqlite3.db'
  )
  
  # Only valid for http://localhost/
  Geokit::Geocoders::google = API_KEY
end


class Idea < ActiveRecord::Base
  # id
  # author
  # title
  # persons
  # duration
  # expenses
  # date
  # location
end


get '/' do
  erb :find
end

get '/create' do
  erb :create
end

post '/create' do
  params[:duration] = params[:duration].to_i # kann text sein wenn 0
  params[:persons] = params[:persons].to_i + 1 # man selbst gehört auch dazu
  idea = Idea.new(params)
  idea.save
  redirect '/create'
end

delete '/delete' do
  idea = Idea.find(params[:id])
  idea.destroy
  redirect '/list'
end

get '/list' do
  @ideas = Idea.find(:all)
  erb :list
end

post '/suggest_location.json' do
  geo = Google::Geo.new API_KEY
  addresses = geo.locate(params[:q] + " schweiz") # HACK: Eingrenzen auf die Schweiz
  {"results" => addresses.map{|a| a.to_s}}.to_json
end

get '/location' do
  peter = GoogleGeocoder.geocode 'Zentralstrasse 118 Wettingen Schweiz'
  benji = GoogleGeocoder.geocode 'Rathausgässli 31 Lenzburg Schweiz'
  haml "%p Von Peter zu Benji sind es #{peter.distance_to(benji).round} Kilometer"
end

get '/weather' do
  response = YahooWeather::Client.new.lookup_location("SZXX0033", 'c')
  [response.title, response.condition.temp, response.condition.text].join("\n")
end

not_found do
  "Nüüt gits!"
end

error do
  "Pfui, bäh, en Fehler!"
end

helpers do
  def l(title, path = nil, attributes = {})
    attributes[:href]  = path ||= title.downcase
    attributes[:class] = "active" if request.path_info =~ /#{path}$/i
    "<a #{attributes.map{|a,v| "#{a}='#{v}'"}.join(" ")}>#{title}</a>"
  end
  
  def body_class
    text = request.path_info.gsub("/", "")
    text.empty? ? "index" : text
  end
end