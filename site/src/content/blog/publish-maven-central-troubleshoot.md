---
title: "Publishing to Maven central (troubleshoot included)"
description: "I tried to follow the OSSRH guide but I had lots of issues. I hope this \"guide of the guide\" saves you time and frustration. Examples and commands included."
pubDate: "2016-11-21T13:50:42"
heroImage: "../../assets/blog/uploads/2017/11/KgcUeAo.png"
tags: ["java", "tutorial"]
categories: ["Technology"]
---

## It's a treacherous path. Bring food and a sword.

I tried to follow the official [OSSRH guide](http://central.sonatype.org/pages/ossrh-guide.html) but I had so many issues... I hope this "guide of the guide" saves you time and frustration.

## First things first

1. [Create a JIRA account](https://issues.sonatype.org/secure/Signup!default.jspa) on Sonatype's environment.
2. [Create a ticket](https://issues.sonatype.org/secure/CreateIssue.jspa?issuetype=21&pid=10134) for the creation of your repo.
3. [Wait](http://central.sonatype.org/articles/2014/Feb/27/why-the-wait) 2 business days.

## While you wait...

Make sure you have a `pom.xml` that complies with [Sonatype's requirements](http://central.sonatype.org/pages/requirements.html), namely:

- Project coordinates (`groupId`, `artifactId`, `version`)
- Name, Description and URL
- License info
- Developer info
- Source control (repo) info

It will only get worse from here. You have been warned.

_scroll down if you're brave_

## Setting up the deployment path

When you get the reply to your Jira Ticket:

- [Watch this video guide](https://www.youtube.com/watch?v=dXR4pJ_zS-0)
- But keep an open tab with the [complete guide](http://central.sonatype.org/pages/apache-maven.html), to copy-paste code snippets.

### Problem (path to config)

I got a `401` after doing `mvn deploy`.

To solve this, run `mvn -X` and search for `Reading user settings from`. That's the folder where you need your `settings.xml` to be.

Mine was `[DEBUG] Reading user settings from /Users/USERNAME/.m2/settings.xml`

### Problem (ups)

`Missing: no sources jar found in folder`

Add plugins for source and javadoc, copy-paste from [this section](http://central.sonatype.org/pages/apache-maven.html#javadoc-and-sources-attachments).

### Problem (signing code)

`Missing Signature (...) *.jar.asc' does not exist for`

Check if you have GPG installed with `gpg --version`. In my case it wasn't. To install it, using [homebrew](http://brew.sh/), just do...

```
brew install gnupg
brew install gnupg2
PATH="/usr/local/opt/gnupg/libexec/gpgbin:$PATH"
```

- Follow [these steps](http://central.sonatype.org/pages/working-with-pgp-signatures.html) to generate a signature.
- Edit `settings.xml` again to include your GPG passphrase.

```xml
<settings>
  <servers>
    <server>
      <id>ossrh</id>
      <username>sonatype credentials</username>
      <password>sonatype credentials</password>
    </server>
  </servers>
</settings>
```

- You must sign at least one file before being able to send the key to the server. You can use any file and delete it afterwards.

For some reason you have to use your passphrase at least once, to "unlock it" or some kind... make sure you are [able to receive the key back from the server](http://central.sonatype.org/pages/working-with-pgp-signatures.html#distributing-your-public-key).

## (Attempting to) Publish on Maven Central

```
mvn release:clean release:prepare
```

and press **Enter** to use the defaults

```
What is the release version for "GroupId:ArtifactId"?
What is SCM release tag or label for "GroupId:ArtifactId"?
What is the new development version for "GroupId:ArtifactId"?
```

TIP: more about preparing your code for maven releases [here](http://maven.apache.org/maven-release/maven-release-plugin/examples/prepare-release.html).

### Problem (signing failed: Inappropriate ioctl for device)

[You need to](https://stackoverflow.com/a/57591830/675577) add these two lines to your bash profile (`~/.bash_profile` or `~/.zshrc`):

```
GPG_TTY=$(tty)
export GPG_TTY
```

Then restart your terminal.

### Problem (signing failed: No such file or directory)

[Add this code snippet](https://github.com/samuelmeuli/action-maven-publish/issues/3#issuecomment-566532938) to your `pom.xml`, inside the `maven-gpg-plugin` plugin:

```
<configuration>
  <!-- Prevent `gpg` from using pinentry programs -->
  <gpgArguments>
    <arg>--pinentry-mode</arg>
    <arg>loopback</arg>
  </gpgArguments>
</configuration>
```

### Problem (accessing git)

`You can't push to git...`

I had to edit my `pom.xml`, according to this precious [SO answer](http://stackoverflow.com/a/20581541/675577) to look like this:

```
<scm>
    <connection>scm:git:git@github.com/USER/REPO.git</connection>
    <developerConnection>scm:git:git@github.com:USER/REPO.git</developerConnection>
    <url>git@github.com/USER/REPO/tree/master</url>
</scm>
```

### Problem (git credentials)

```
[ERROR] Failed to execute goal org.apache.maven.plugins:maven-release-plugin:2.5.3:prepare (default-cli): Unable to commit files
[ERROR] Provider message:
[ERROR] The git-push command failed.
[ERROR] Command output:
[ERROR] Permission denied (publickey).
```

It was my problem since I could not reach github from my machine (see below)

```
$ ssh -T git@github.com
Permission denied (publickey).
```

I had to [add an SSH key](https://help.github.com/articles/generating-a-new-ssh-key-and-adding-it-to-the-ssh-agent/) to my account for my machine.

## Done

Next time, you just need to

`mvn release:clean release:prepare mvn release:perform git push`
