class AddIdeas < ActiveRecord::Migration
   def self.up
     create_table :ideas do |t|
       t.string  :author
       t.string  :title
       t.integer :participants
       t.integer :duration
       t.integer :expenses
       t.integer :time
       t.integer :weather
       t.timestamps
     end
   end

   def self.down
     drop_table :ideas
   end
 end