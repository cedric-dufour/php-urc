PHP Universal Remote Control (PHP-URC)
======================================


Synopsis
--------

The PHP Universal Remote Control (PHP-URC) framework is a library which allows
low-skilled PHP developers to easily design web-based remote control interfaces.

As such, it comes as an ideal development resource for those setting-up a Home
Theater PC (HTPC) or a Home Control PC (HCPC), and willing to control both their
PC and external appliances - amplifier, beamer, coffee machine, etc. - from a
single interface, accessible with any web-able device.

From the front-end (user) interface point of view, PHP-URC is entirely Ajax-
based and provides a "look-and-feel" close to a genuine remote control.

From the back-end (services/appliances) interface point of view, PHP-URC relies
on "control" plug-ins, which can easily be extended to drive any imagineable
backend.

The current packaging includes plug-ins for the following backends:
 - LIRC (Linux Infra-Red Control) - http://lirc.org
 - HEYU (X-10 Automation) - http://heyu.org
 - MPD (Music Player Daemon) - http://www.musicpd.org
 - MPLAYER (Multimedia Player) - http://www.mplayerhq.hu
 - VLC (VideoLAN Media Player) - http://www.videolan.org
 - SHELL (Command Line) - allowing to send arbitrary system commands
 - MACRO (Macro Commands) - allowing to gather several control/command as one


Dependencies
------------

 - [MUST] PHP 5.2 or later (it may work on earlier PHP 5 versions; this is untested though)


Features
--------

 - Standard API for interfacing with media players
 - Ajax-based user (web) interface
 - Ready-to-use "controls" (push buttons, switch buttons, rotary buttons,
   horizontal and vertical sliders, text display)
 - Visible feedbacks for user actions and backend responses

