# Monocle WordPress by Spur
Analyze WordPress forms usage from a user session coming from a residential proxy, malware proxy, or other endpoint based proxy network.

## Description

Monocle can detect a user session coming from a residential proxy, malware proxy, or other endpoint based proxy network. By detecting this at the session level, you can take action on abusive users without impacting legitimate ones.

[Monocle](https://spur.us/monocle)  
[Docs](https://docs.spur.us/#/monocle)  
[FAQ](https://spur.us/monocle/#faqs)  
[Demo](https://spur.us/app/demos/monocle/form)  
[Blog](https://spur.us/announcing-monocle-community-edition)  

## Help and Support

support@spur.us

### Installation

1. Add the monocle plugin from the Plugins menu in your WordPress admin console.
2. Activate the plugin
3. Configure your monocle site token and decrypt token in Settings/Monocle Settings from the WordPress admin console.
4. Ensure that any forms you create have "monocle-enriched" as a class on the \<form\> element, the library will automatically append the latest client threat bundle to the form data with the id "monocle".

### Frequently Asked Questions

### How to get a monocle site token and decrypt token?

Follow the next steps in order to get and enable monocle protection:
1. Create an account at spur.us
2. Navigate to the dashboard.
3. Click the monocle tab at the top.
4. Click the create deployment button.
5. You will see your site and decrypt tokens. Copy them and paste to the appropriate fields on plugin Settings page.
6. Save changes.

### How to deploy for testing and development?

This repository contains a Docker compose file which will deploy a new WordPress site locally with Monocle installed.
Note: This uses the [official WordPress docker image](https://hub.docker.com/_/wordpress), but it is NOT configured for production.

1. Check out this repository on a machine running Docker
2. Run `docker compose up`
3. Visit http://localhost:8000 and set up WordPress
4. Activate the Monocle plugin in the [WordPress admin console](http://localhost:8000/wp-admin/plugins.php)
5. Configure Monocle under the Settings/Monocle Settings menu

## Changelog

### V1.0
Ability to add Monocle to forms.