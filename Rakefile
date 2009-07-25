require 'rake'
require 'activerecord'

ENV['APP_ENV'] ||= 'development'

namespace :db do
  desc "Connect to the database"
  task :environment do
    config = YAML::load(File.open(File.join(File.dirname(__FILE__), 'db', 'database.yml')))
    ActiveRecord::Base.configurations = config
    ActiveRecord::Base.establish_connection(ENV['APP_ENV'])
    ActiveRecord::Base.logger = Logger.new(STDOUT)
  end

  desc "Migrate the database"
  task :migrate => :environment do
    ActiveRecord::Migration.verbose = true
    ActiveRecord::Migrator.migrate("db/migrate")
  end
  
  desc "Rollback to previous migration"
  task :rollback => :environment do
    ActiveRecord::Migration.verbose = true
    ActiveRecord::Migrator.rollback("db/migrate/", 1)
  end
end