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
