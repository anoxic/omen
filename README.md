[![Build Status](https://travis-ci.org/anoxic/omen.svg?branch=master)](https://travis-ci.org/anoxic/omen)

A simple status scheck that can send SMS and text-to-speach calls on failure.

**Note:** this is in progress and not yet fully functioning. See [TODO](TODO) for more information.


SOME THOUGHTS:


A: we should operate in "ticks" from each error,
   which will in this case be 60 second blips
   this will allow us to just operate with
   `($blips - $delay) % $minutes == 0`
   to test if a message needs to be sent to
   a contact
   
B: we should store a status file containing the
   timestamp of the last error, and then subtract
   the current timestamp + divide to get the blip
   we're on right now
   
C: after testing for an error, we should retry
   after 5, 10, and 15 seconds to make sure it's
   not a false positive
   
D: each check should write to it's own log file
   to mark when errors start and stop
   
E: the should be a file that contains the last
   run just in case (this is so you can sanity
   check to see if omen was running)


-----

NOTES:


these files are created by omen:

   REPO/omen.status  
   REPO/CHECK.status  
   REPO/CHECK.log


the log and status should follow
this format:

   OK <timestamp>
   ERROR <timestamp>


this link is so i don't forget
where to send texts from:

   https://dashboard.nexmo.com/private/dashboard (sms/tts)

