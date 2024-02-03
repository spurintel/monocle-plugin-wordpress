=== Monocle by Spur ===
Contributors: Spur
Tags: monocle, spur, security, fraud, captcha, recaptcha, vpn, proxy, Invisible reCaptcha, Invisible captcha, captha, cpatcha
Requires at least: 6.0
Requires PHP: 7.0
Tested up to: 6.4.3
Stable tag: 1.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Protect WordPress forms from a user session coming from a residential proxy, malware proxy, or other endpoint based proxy network.

== Description ==

Monocle can detect a user session coming from a residential proxy, malware proxy, or other endpoint based proxy network. By detecting this at the session level, you can take action on abusive users without impacting legitimate ones.

[Monocle](https://spur.us/monocle)
[Docs](https://docs.spur.us/#/monocle)
[FAQ](https://spur.us/monocle/#faqs)
[Demo](https://spur.us/app/demos/monocle/form)
[Blog](https://spur.us/announcing-monocle-community-edition)

= Help & Support =

support@spur.us

== Installation ==

1. Add the Monocle plugin from the Plugins menu in your WordPress admin console.
2. Activate the plugin.
3. Configure your monocle site token and decrypt token in Settings/Monocle Settings from the WordPress admin console.

== Frequently Asked Questions ==

= How to get a monocle site and decrypt token? =

Follow the next steps in order to get and enable monocle protection:
1. Sign up for an account at [Spur](https://spur.us).
2. Click the Monocle tab at the top.
3. Click the create deployment button.
4. You will see your site and decrypt tokens. Copy them and paste to the appropriate fields on plugin Settings page.
5. Save changes.

= What pages does Monocle protect? =

When enabled, protects the standard WordPress register, login, lost password, and comment pages.
By default Monocle is in "Log Only" mode.  Additional options are located in the `Settings > Monocle Settings` menu.
 
= Can I protect other forms? =

Ensure that any forms you create have "monocle-enriched" as a class on the \<form\> element, 
the library will automatically append the latest client threat bundle to the form data with the id "monocle".

== Changelog ==

= V1.0 =
Ability to add Monocle into standard forms.