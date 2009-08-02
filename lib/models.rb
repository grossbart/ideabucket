class Idea < ActiveRecord::Base
  # participants (X+)
  # time (any, evening, weekend)
  # duration (X-)
  # expenses (0, +/- X)
  # weather (gut, schlecht, warm, regnerisch, schneeig)
  def self.find_matches(params)
    query = {}
    if params[:participants].to_i > 0
      query[:participants] = params[:participants].to_i...1000
    end
    if params[:expenses].to_i > 0
      query[:expenses] = 0...params[:expenses].to_i
    end
    self.find(:all, :limit => 5, :conditions => query)
  end
end