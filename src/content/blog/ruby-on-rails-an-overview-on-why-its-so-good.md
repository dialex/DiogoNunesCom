---
title: "Ruby on Rails: an overview on why it's so good"
description: "Looks like everyone is using Ruby on Rails, but why? Let me tell you what awaits you if you choose Rails and why you should choose it."
pubDate: "2014-05-05T08:00:00"
heroImage: "../../assets/blog/uploads/2014/05/try-ruby-e1373488411601.jpg"
heroCaption: "[]1 www.tryruby.org"
tags: ["rails", "review"]
categories: ["Technology"]
---

## I was in denial for a while but now I get why Rails is so good.

First things first, what is Rails anyway? [Ruby on Rails](http://rubyonrails.org/) is a framework that allows you to develop web applications using the [MVC](http://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller) design pattern. Generally speaking a [framework](http://en.wikipedia.org/wiki/Software_framework) is a higher-level abstraction that packages and offers the generic and commonly used functionality. [Ruby](https://www.ruby-lang.org/en/) is a general-purpose programming language, meaning it was not specifically designed for web development, thus adjustments must be made. So what [David H. Hansson](http://david.heinemeierhansson.com/) did at [Basecamp](http://en.wikipedia.org/wiki/37signals) was create a framework that (1) allows you to develop web applications using the Ruby language, and (2) speeds up the development by automagically generating code for commonly used functionality - thus the name "Ruby on Rails".

### What awaits you if you choose Rails

Ruby on Rails allows you to write less code while accomplishing more than with other frameworks. This is possible because Rails is [_opinionated software_](http://quizzsystem.comyr.com/web-page/), i.e. it assumes there's a best way to do things and that you want to do things that way. Basic everyday functionality is either offered by the framework or it can be imported plug'n'play style from the online community. If you do things "the Rails Way" you will be tremendously productive. To do so, you should follow Rails' main principles:

- **Don’t Repeat Yourself (DRY).** Duplicated code is bad. With Rails you can write the code once (called a _part_) and refer to it from another part of your code. Do your webpages share a common header? Don't copy-paste it, just inherit it!
- **Convention Over Configuration.** Rails does most of the work for you, making assumptions about what you want to do and how you want to do it, rather than letting you tweak every little detail through endless configuration files.
- **REST is the best pattern for web applications.** Organizing your application around resources that respond to standard HTTP verbs is the fastest way to go. And since everyone uses this convention you can easily share knowledge and practices.
- **Your code should read as plain English.** This is achieved mostly by Ruby's syntax together with Rails' templating system. And it gets even cleaner if you use _gems_ (off-the-shelf functionality developed by the community) like the [HTML Abstraction Markup Language](http://haml.info/) for webpages or [Capybara](http://www.opinionatedprogrammer.com/2011/02/capybara-and-selenium-with-rspec-and-rails-3/) for tests.

As I mentioned, Rails structures your code around the [Model, View, Controller architecture](http://quizzsystem.comyr.com/web-page/). In summary a Model stores information, a View shows that information to the user, and a Controller replies to web requests by fetching data from the Model and populating the dynamic parts of the View:

- A **Model** represents an entity or a class (remember [OOP](http://en.m.wikipedia.org/wiki/Object-oriented_programming)?) which stores all relevant information and business logic. In most cases, one model in your application will correspond to one table in your database. Example: you create a `User` model with a `Name` `string` field and Rails automatically creates a `Users` table with a `Name` `varchar` column.
- **Views** represent the user interface of your application. In Rails, views are often HTML files with embedded Ruby code (enclosed by `<%= ...ruby code here... %>`) that performs tasks related solely to the presentation of the data. Those Ruby snippets are what puts the dynamic in "dynamic webpages".
- **Controllers** connect models and views. In Rails, controllers are responsible for processing the incoming requests from the web browser, interrogating the models for data, and passing that data on to the views for presentation.

### Why is that good for me?

[Different people](http://blog.codinghorror.com/why-ruby/) will give you [different advantages](http://www.infront.com/blogs/the-infront-blog/2013/1/4/five-reasons-why-we-use-ruby-on-rails) but these four are agreed among most developers:

- **Significant cost savings.** Ruby on Rails is essentially a free development toolkit, which runs on a free operating system (Linux) and works with multiple databases (MySQL, PostgreSQL) and web servers most of which are free. By using a cost-free platform, you're able to significantly reduce costs without sacrificing any speed, security or performance.
- **Rapid development.** Rails is a rapid application development tool because (1) it makes assumptions on what you need and codes it for you, and (2) the community is huge and active so probably there's already a gem for that feature that you were about to implement from scratch. This comes handy on small or innovative projects when you have limited time and resources to create a functional prototype.
- **Structured and standardized code.** Rails favors consistency in the structure and methodology when writing code. The MVC architecture makes it a lot easier to manage the code between developers. This means that an individual developer's coding style doesn't get in the way of writing the code, so passing code from one developer to another creates less friction. This helps you tremendously when you're working on a team or when you want to open-source your project.
- **Collaboration.** The Ruby/Rails community is extremely active and enthusiastic. People are constantly developing code to add more functionality or to integrate with other APIs. Therefore, you have a larger and more diverse toolkit to take advantage of. This also means there are more tutorials, guides, questions and answers online to help you out.

If you're still curious read ["Why do so many startups use Ruby on Rails?"](https://www.quora.com/Ruby-on-Rails-web-framework/Why-do-so-many-startups-use-Ruby-on-Rails)

### Conclusion

For some reason I was suspicious about Ruby on Rails, probably because too many people were saying that it was too good. Meanwhile I tried Django (Python) and then Play Framework (Java/Scala). Finally I decided to stop being prejudiced and gave Rails a chance; I only regret not doing so earlier.
