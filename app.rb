# run with `ruby app.rb`

require 'rubygems'
require 'sinatra'
require 'activerecord'
require 'geokit'
require 'yahoo-weather'

include Geokit::Geocoders
Geokit::default_units = :kilometers

configure do
  ActiveRecord::Base.establish_connection(
    :adapter => 'sqlite3',
    :dbfile =>  'db/app.sqlite3.db'
  )
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
  erb :index
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

get '/location' do
  peter = YahooGeocoder.geocode 'Zentralstrasse 118 Wettingen Schweiz'
  benji = YahooGeocoder.geocode 'Rathausgässli 31 Lenzburg Schweiz'
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