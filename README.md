Launch Daemons Module
==============

Gets data about Launch Daemons and Launch Agents on in the /Library/ and /Users/Library folders. The columns are the keys listed for launchd. To find out a more in depth description for them, run `man launchd.plist` in Terminal.


Table Schema
---

The table contains the following information, one row per Launch Daemon:

* id (int) Unique id
* serial_number (string) Serial Number
* label (string) Daemon's label
* path (text) Location of daemon
* disabled (boolean) Is daemon disabled
* runatload (boolean) Is daemon set to run when loaded
* program (text) What command the daemon executes
* startonmound (boolean) Will daemon run when volume is mounted
* startinterval (int) How many seconds until daemon is run
* keepalive (boolean) Will daemon be kept alive
* daemon_json (text) Textual representation of daemon's contents
