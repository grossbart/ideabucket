class Idea < ActiveRecord::Base
  def self.find_random_by_type(type, value)
    results = find(:all, :conditions => "#{type} = #{value}")
    # No results with specified criteria, resort to all.
    results = find(:all) if results.empty?
    results[rand(results.size)]
  end
end