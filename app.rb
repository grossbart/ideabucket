# -*- coding: utf-8 -*-
# run with `ruby app.rb`

require 'rubygems'
require 'compass' #must be loaded before sinatra
require 'sinatra'
require 'haml'    #must be loaded after sinatra
require 'activerecord'
require 'lib/models'
require 'lib/helpers'

ENV['APP_ENV'] ||= 'development'

# set sinatra's variables
set :app_file, __FILE__
set :root, File.dirname(__FILE__)
set :views, "views"

#
# Configuration
#
configure do
  # Active Record
  config = YAML::load(File.open(File.join(File.dirname(__FILE__), 'db', 'database.yml')))
  ActiveRecord::Base.configurations = config
  ActiveRecord::Base.establish_connection(ENV['APP_ENV'])

  # Configure Compass
  Compass.configuration do |config|
    config.project_path = File.dirname(__FILE__)
    config.sass_dir = File.join(Sinatra::Application.views, 'stylesheets')
    config.output_style = :compact
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
# Sass
#
get '/stylesheets/:name.css' do
  content_type 'text/css', :charset => 'utf-8'
  sass :"stylesheets/#{params[:name]}", Compass.sass_engine_options
end


#
# Frontpage: Find ideas
#
get '/' do
  haml :find
end

post '/find' do
  (@ideas = Idea.find_matches(params)) ? @ideas.to_json : {}
end


#
# Create ideas
#
get '/create' do
  haml :create
end

post '/create' do
  params[:duration] = params[:duration].to_i   # kann text sein wenn 0
  params[:participants] = params[:participants].to_i + 1 # man selbst gehört auch dazu
  Idea.create(params)
  redirect '/create'
end


#
# Administer ideas
#
get '/list' do
  @ideas = Idea.find(:all)
  haml :list
end

delete '/delete' do
  idea = Idea.find(params[:id])
  idea.destroy
  redirect '/list'
end
