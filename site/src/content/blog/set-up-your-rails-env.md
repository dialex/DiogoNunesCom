---
title: "Setting up your Rails environment from scratch"
description: "This is a complete and summarized guide to set up a Ruby on Rails development environment on Ubuntu."
pubDate: "2014-05-12T08:00:00"
heroImage: "/blog/uploads/2014/05/rails-worker-assembly-construction.jpg"
heroCaption: "original photo: goo.gl/QE43FV"
tags: ["coding", "rails", "tutorial", "web dev"]
categories: ["Technology"]
---

## This is a complete yet summarized guide to set up a Ruby on Rails development environment on Linux.

Installing and configuring Sublime Text, Github, and Heroku are optional but we're included for the sake of completeness. Since these are the _de facto_ tools for editing source code, managing code repositories and deploying applications on the Rails world, you'll probably want to install them and get used to them.

Assuming you already have [Ubuntu](http://www.ubuntu.com/download/desktop) installed, let's start!

## Installing Ruby, the core language

Install Ruby's dependencies first:

```
sudo apt-get update
sudo apt-get install git-core curl zlib1g-dev build-essential libssl-dev libreadline-dev libyaml-dev libsqlite3-dev sqlite3 libxml2-dev libxslt1-dev
```

Install [rvm](http://rvm.io/) (ruby version manager), a command-line tool which allows you to easily install, manage, and work with multiple ruby environments:

```
sudo apt-get install libgdbm-dev libncurses5-dev automake libtool bison libffi-dev
curl -L https://get.rvm.io | bash -s stable
source ~/.rvm/scripts/rvm
echo "source ~/.rvm/scripts/rvm" >> ~/.bashrc
rvm get stable                                  # updates to the latest  version
```

Install Ruby using rvm:

```
rvm install 2.1.0               # installs ruby 2.1.0, it might take a while
rvm use 2.1.0 --default
ruby -v                         # should print your ruby's version
```

It's probably a good idea to install a [YAML](https://en.wikipedia.org/wiki/YAML) library, as you might need it later:

```
apt-get install libyaml-dev     # For Debian-based Linux systems
yum install libyaml-devel       # For Fedora/CentOS/RHEL Linux systems
brew install libyaml            # For Mac with Homebrew
```

Finally tell [RubyGems](http://rubygems.org/) (which was installed with Ruby and is responsible for managing gems) not to install the documentation for each package locally:

```
echo "gem: --no-ri --no-rdoc" > ~/.gemrc
```

## Installing Rails, the framework

Nowadays almost everything uses Javascript, therefore we'll need a JS runtime. Among other things, this lets you use the Asset Pipeline in Rails which combines and minifies your .js files for a faster production environment. We'll install Node.JS as our Javascript runtime:

```
sudo add-apt-repository ppa:chris-lea/node.js
sudo apt-get update
sudo apt-get install nodejs
```

Time to lay down the _rails_ for your _train_ ([get it?](http://youtu.be/6zXDo4dL7SU)):

```
gem install rails
rails -v                    # should print your Rails version
```

## Installing Sublime Text, the code editor

[Sublime Text](http://www.sublimetext.com/) is a elegant, customizable, and powerful text/code editor. Here's [why you should use it](http://blog.codeclimate.com/blog/2012/06/21/sublime-text-2-for-ruby/). Customizing it would need another guide, for now let's just [install it](http://askubuntu.com/a/227617/17727):

```
# For Sublime-Text-2
sudo add-apt-repository ppa:webupd8team/sublime-text-2
sudo apt-get update
sudo apt-get install sublime-text
# For Sublime-Text-3 (beta)
sudo add-apt-repository ppa:webupd8team/sublime-text-3
sudo apt-get update
sudo apt-get install sublime-text-installer
```

## Configuring Git(hub), the code repository

When you installed Ruby's dependencies you installed [Git](http://git-scm.com/). I strongly advise you to use Git as your version control system. If you're planning to develop alone, you can start using Git locally right away, just execute `git init` on your application's folder.

However, if you want to push your local changes to a remote code repository or share your code online and let others fork it, you should [create a free Github account](https://github.com/join). Once you have your Github account:

```
git config --global color.ui true
git config --global core.editor "subl -w"               # use Sublime Text as the default code editor
git config --global user.name "Alice Wonderland"        # replace with your name
git config --global user.email "alice@wonderland.com"   # replace with your email
ssh-keygen -t rsa -C "alice@wonderland.com"    
```

The last command will generate a public and a private SSH key. This keys are used to authenticate you before you push changes to the online repository. You'll get something like this:

```
Generating public/private rsa key pair.
Enter file in which to save the key (/home/alice/.ssh/id_rsa):       # press enter to use default location
Created directory '/home/alice/.ssh'.
Enter passphrase (empty for no passphrase):                          # optional, encrypts your private key 
Your identification has been saved in /home/alice/.ssh/id_rsa.       # private key, DO NOT share it
Your public key has been saved in /home/alice/.ssh/id_rsa.pub.       # public key, you need to send this to github
```

To copy the public key, open it on a text editor, and copy the file's contents:

```
gedit /home/alice/.ssh/id_rsa.pub
```

Go to your [Github's Account Settings > SSH keys > Add SSH keys](https://help.github.com/articles/generating-ssh-keys#step-3-add-your-ssh-key-to-github). Give it any title and paste the copied public key.

## Configuring the database

Rails ships with sqlite3 as the default database. Chances are you won't want to use it because it's stored as a simple file on disk. You'll probably want something more robust like [MySQL](http://www.mysql.com/) (Rails' default) or [PostgreSQL](http://www.postgresql.org/) (Heroku's default) or both!

I use MySQL for development and testing and PostgreSQL for production. Since Rails is database agnostic, as long as you correctly configure your databases (`databases.yml` file) everything will work just fine.

### PostgreSQL

The PostgreSQL installation doesn't setup a user for you, so you'll have to do it manually after you install it:

```
sudo sh -c "echo 'deb http://apt.postgresql.org/pub/repos/apt/ precise-pgdg main' > /etc/apt/sources.list.d/pgdg.list"
wget --quiet -O - http://apt.postgresql.org/pub/repos/apt/ACCC4CF8.asc | sudo apt-key add -
sudo apt-get update
sudo apt-get install postgresql-common
sudo apt-get install postgresql-9.3 libpq-dev   # installs postgresql version 9.3
```

Let's create a user with permissions to create databases. Attention: the name of the postgres user should be the name of the user currently logged in on your operating system - therefore you will praobably create a user called `john` instead of the usual `admin` or `sa`.

```
sudo -u postgres createuser alice -s    # the currently logged user's name
```

Now for the password:

```
sudo -u postgres psql           # start postgres
\password alice                 # tell it you want to change your password
Enter new password:             # do it 
\q                              # quit when you're done
```

Later on, after Rails creates your application you should configure the `database.yml` file with the user and password you just created on the previous step.

## Installing Heroku, the production environment

Once you have your application working locally you'll want to deploy it online so that user can... well, use it! [Heroku](https://www.heroku.com/) integrates really well with Rails and its free. So [sign up](https://id.heroku.com/signup) and [install Heroku's toolbelt](https://toolbelt.heroku.com/debian):

```
wget -qO- https://toolbelt.heroku.com/install-ubuntu.sh | sh    # Debian or Ubuntu
heroku login                                                    # type in your credentials
heroku keys:add                                                 # this will associate your github's SSH keys with heroku
```

## Done... but is it working?

To test your Ruby/Rails installation:

```
rails new myapp

#### If you want to use MySQL
rails new myapp -d mysql

#### If you want to use Postgres
# Note that this will expect a postgres user with the same username
# as your app. You should edit the file at myapp/config/database.yml
# to match the user you created earlier
rails new myapp -d postgresql

# Move into the application directory
cd myapp

# If you setup MySQL or Postgres with a username/password, modify the
# config/database.yml file to contain the username/password that you specified    

rake db:create  # Create the database    
rails server    # And open your browser at the url output by this command
```

This tutorial was based on [GoRails'](http://gorails.com/setup/ubuntu/13.10). To test the whole development workflow (from zero to deploy) follow [Michael Hartl's Rails Tutorial](https://www.railstutorial.org/).
