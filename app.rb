# run with `ruby app.rb`

require 'rubygems'
require 'sinatra'
require 'geokit'
require 'yahoo-weather'

include Geokit::Geocoders
Geokit::default_units = :kilometers

get '/' do
  erb :index
end

get '/create' do
  erb :create
end

get '/location' do
  peter = YahooGeocoder.geocode 'Zentralstrasse 118 Wettingen Schweiz'
  wilma = YahooGeocoder.geocode 'Fliederweg 14 Seon Schweiz'
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

get '/stylesheet.css' do
  content_type 'text/css', :charset => 'utf-8'
  sass :stylesheet
end