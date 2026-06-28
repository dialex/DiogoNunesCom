---
title: "Pipeline triggering pipeline on GitLab"
description: "How can you have a GitLab pipeline triggering another pipeline and wait for the result? Code snippet included."
pubDate: "2020-05-25T07:00:29"
heroImage: "/blog/uploads/2020/04/s270904456481439361_p798_i2_w1799.jpeg"
tags: ["CI Pipelines", "coding", "GitLab"]
categories: ["Technology"]
---

[](/blog/uploads/2020/04/s270904456481439361_p798_i2_w1799.jpeg)

Usually when configuring a GitLab pipeline (`.gitlab-ci.yml`) you only care about steps in your own pipeline. Sometimes however, you may want to trigger other pipelines (of other repositories) as a step of your own pipeline.

> Context: You have your app divided into multiple microservices/repos. There's another repo which contains a suite of End-to-End tests. That repo has a pipeline that, when triggered, runs the whole tests using the latest version of each app.
> 
> Problem: At the end of your microservice's pipeline, you deploy your latest version, and then you want to run the E2E pipeline, to make sure you didn't break any previously working functionality. But that's another repo...

So the question is: **how can you have a pipeline triggering another pipeline and _wait_ for the result?**

~Officially, you can't~ At the time, we were not able to use [the official way](https://docs.gitlab.com/ee/ci/multi_project_pipelines.html). Luckily for us, [Sven A. Schmidt created a workaround](https://gitlab.com/finestructure/pipeline-trigger). The example below was adapted from his solution:

```yaml
variables:
  GITLAB_API_PATH: /api/v4/projects
  GITLAB_HOST: TBD            # domain where your gitlab is hosted
  PIPELINE_TRIGGER_IMAGE: registry.gitlab.com/finestructure/pipeline-trigger 
  # Secrets
  PIPELINE_SERVICE_TOKEN: TBD # token of user that can invoke pipelines
  PIPELINE_TRIGGER_TOKEN: TBD # token of the invoked repo, here's how http://tiny.cc/2unvbz

trigger-pipeline:
  image: $PIPELINE_TRIGGER_IMAGE
  variables:
    GIT_STRATEGY: none
    TRIGGER_TARGET_REPO_ID: TBD # the GitLab ID of the repo you want to call
  script:
    - trigger --verbose -h $GITLAB_HOST -u $GITLAB_API_PATH
      -a $TRIGGER_SOURCE_USER_TOKEN -p $TRIGGER_TARGET_REPO_TOKEN
      -t master $TRIGGER_TARGET_REPO_ID
      -e SOME_ENV_VAR="value goes here" -e ANOTHER_ENV_VAR=$CAN_ALSO_READ_FROM_VARS
```

If you use this code you will create a job named `trigger-pipeline` in your pipeline A, that when executed will call a pipeline B (`TRIGGER_TARGET_REPO_ID`), and wait for that pipeline to finish.

- Don't forget to replace the `TBD` (To Be Defined) values with your own configuration.
- I was a bit lost on how to create the `PIPELINE_TRIGGER_TOKEN`, then I [found this](http://tiny.cc/2unvbz)
- This code assumes you have GitLab self hosted. If not, you can remove the `-h $GITLAB_HOST -u $GITLAB_API_PATH` part.
- The end status of pipeline B will be the status of this job. So if pipeline B fails, this job will also fail.
