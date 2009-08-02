class AddIdeas < ActiveRecord::Migration
   def self.up
     create_table :ideas do |t|
       t.string  :author, :default => "Anonym"
       t.string  :title, :default => ""
       t.integer :participants, :default => 1
       t.integer :duration, :default => 3
       t.integer :expenses, :default => 0
       t.integer :time, :default => 0
       t.integer :weather, :default => 0
       t.timestamps
     end
   end

   def self.down
     drop_table :ideas
   end
 end