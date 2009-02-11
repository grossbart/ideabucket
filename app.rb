# run with `ruby app.rb`

require 'rubygems'
require 'sinatra'
require 'activerecord'
require 'geokit'
require 'yahoo-weather'

# The gem is momentarily disabled as there are important bugfixes in our own copy
# require 'google/geo'
require 'lib/google/geo'


#
# Configuration
#
API_KEY = 'ABQIAAAAKkxF5_xalx0zhXRdE2dXBhT2yXp_ZAY8_ufC3CFXhHIE1NvwkxSjTiBzhT9UNDbF96KLnxX3-7jrGg' unless defined? API_KEY
include Geokit::Geocoders
Geokit::default_units = :kilometers

configure do
  ActiveRecord::Base.establish_connection(
    :adapter => 'sqlite3',
    :dbfile =>  'db/app.sqlite3.db'
  )
  # Only valid for http://localhost:4567/
  Geokit::Geocoders::google = API_KEY
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


#
# Model
#
class Idea < ActiveRecord::Base
  #  -----------------------------------------------------------------------
  # | id | author | title | persons | duration | expenses | date | location |
  #  -----------------------------------------------------------------------
  def self.find_random_by_type(type, value)
    results = find(:all, :conditions => "#{type} = #{value}")
    # No results with specified criteria, resort to all.
    results = find(:all) if results.empty?
    results[rand(results.size)]
  end
end


#========================================#
#     ROUTING AND REQUEST HANDLING
#========================================#

#
# Error handling
#
not_found { "Die angeforderte Seite wurde nicht gefunden." }
error { "Ein Fehler ist aufgetreten" }


#
# Frontpage: Find ideas
#
get '/' do
  erb :find
end

post '/find' do
  @idea = Idea.find_random_by_type(params[:id], params[:value])
  erb :result, :layout => false
end


#
# Create ideas
#
get '/create' do
  erb :create
end

post '/create' do
  params[:duration] = params[:duration].to_i   # kann text sein wenn 0
  params[:persons] = params[:persons].to_i + 1 # man selbst gehört auch dazu
  Idea.create(params)
  redirect '/create'
end


#
# Administer ideas
#
get '/list' do
  @ideas = Idea.find(:all)
  erb :list
end

delete '/delete' do
  idea = Idea.find(params[:id])
  idea.destroy
  redirect '/list'
end

post '/suggest_location.json' do
  geo = Google::Geo.new API_KEY
  addresses = geo.locate(params[:q] + " schweiz") # HACK: Eingrenzen auf die Schweiz
  {"results" => addresses.map{|a| a.to_s}}.to_json
end


#========================================#
#             EXPERIMENTAL
#========================================#

get '/location' do
  peter = GoogleGeocoder.geocode 'Zentralstrasse 118 Wettingen Schweiz'
  benji = GoogleGeocoder.geocode 'Rathausgässli 31 Lenzburg Schweiz'
  haml "%p Von Peter zu Benji sind es #{peter.distance_to(benji).round} Kilometer"
end

get '/weather' do
  response = YahooWeather::Client.new.lookup_location("SZXX0033", 'c')
  [response.title, response.condition.temp, response.condition.text].join("\n")
end
