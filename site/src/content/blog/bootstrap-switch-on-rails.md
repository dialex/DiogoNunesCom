---
title: "Using Bootstrap Switch 3 on Rails"
description: "Replace Bootstrap's checkboxes and radio buttons by an elegant switch... a Bootstrap Switch."
pubDate: "2014-06-09T08:00:46"
heroImage: "../../assets/blog/uploads/2014/06/bootstrap-switch-3-demo-examples.png"
tags: ["rails", "tutorial", "web dev"]
categories: ["Technology"]
---

## Replace Bootstrap's checkboxes and radio buttons by an elegant switch... a Bootstrap Switch.

I'm a great fan of [Twitter's Bootstrap](http://getbootstrap.com/). Recently I had to develop a settings page which involved displaying a lot of Yes/No checkboxes. It looked outdated. **I wanted something a bit more "app-like" - a Yes/No switch**. However **Bootstrap does not include one**, so I found an [open-source bootstrap-themed switch](http://www.bootstrap-switch.org/). The contributors are amazing: I use Bootstrap Switch since version 2 and when Bootstrap 3 came out I requested a visual update so that the switch looked coherent with Bootstrap 3 - a couple of weeks later my request was fulfilled!

How can you use this Bootstrap Switch with Ruby on Rails? Well [it was not that simple](http://stackoverflow.com/questions/22883904/using-bootstrap-switch-with-rails-check-box) so **here goes how I managed to make it work**. By the way, using Bootstrap Switch 3 does not force you to use version 3 of Twitter Bootstrap; I tested it with Bootstrap 2 and it worked perfectly.

## Set up

- Add `gem 'bootstrap-switch-rails', '~> 3.0.0'` to your `Gemfile`
- Add `//= require bootstrap-switch` to `/assets/javascripts/application.js`
- Add `*= require bootstrap3-switch` to `/assets/stylesheets/application.css`

If you use SASS, add `@import "bootstrap3-switch";` to the top of your css file that contains your custom css styles (mine is `app/assets/stylesheets/custom.css.scss`)

## Use it

Every view (`*.html.erb`) that has a Bootstrap Switch needs a bit of Javascript for the switch to work. Since you want the switch to display correctly as soon as the webpage is loaded, I recommend that you put the JS code on the page's header (instead of the footer, as I usually do with JS code that runs on background). The Rails code for that:

```
<% content_for :head do %>
  <script type="text/javascript">
  $(document).ready(function() {
    $('input:checkbox').bootstrapSwitch();
  });
  </script>
<% end %>
```

Notice that `$('input:checkbox').bootstrapSwitch();` will affect ALL checkboxes on that page. If you want more control you can specify the checkbox id, like so `$("[name='my-checkbox-id']").bootstrapSwitch();`. Don't forget to add that same id to your checkbox.

To display the switch on a form together associated with a field just:

```
<%= f.label :field_name, "Field Description" %>
<%= f.check_box :field_name %>
```

That will display the default switch. If you want to **customize the switch** you just need to edit the second line. For instance, I wanted to display a Yes/No switch, with the Yes option on green (Bootstrap's `success` class), and a bit smaller than the default size. The code for that customization is below:

```
<%= f.check_box :field_name,
    :data => { :size=>'small', 'on-color'=>'success', 'on-text'=>'YES', 'off-text'=>'NO' } %>
```

Examples [here](http://www.jque.re/plugins/version3/bootstrap.switch/), and interactive examples [here](http://www.bootply.com/92189#).

Don't forget to [thank the developers by giving the project a star on github](https://github.com/nostalgiaz/bootstrap-switch/) ;)

* * *
