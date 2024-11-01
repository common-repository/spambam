
=== SpamBam ===

Contributors: Gareth Heyes
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=gareth%40businessinfo%2eco%2euk&item_name=SpamBam&item_number=1&amount=5%2e00&no_shipping=1&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8
Tags: spam, comment, comments, spam
Requires at least: 2.0.7
Tested up to: 2.1.2
Stable tag: 2.1


Prevents comment spam and fights against comment spammers by delaying their attempts


== Description ==

= ENGLISH: = 

**Plugin Features:**

* New features include: no cookies, randomised PHP/JS
* No more comment spam
* Delays spammers by 30 seconds
* No captchas required!

== Installation ==

1. Download the current release
2. Unzip and copy the whole spambam folder to your plugins directory.
3. Enable SpamBam in the WP Admin >> Plugins section.
4. Done!

... and you are ready to go!



== Frequently Asked Questions ==


= I have a big site, can I use SpamBam? = 

Yes SpamBam will work with a large amount of traffic, however you might want to reduce the sleep() timer or disable altogether. You can change this in the spambam.php file to any value desired. The constant variable SPAMBAM_SPAMMER_DELAY is used to define the seconds delay.