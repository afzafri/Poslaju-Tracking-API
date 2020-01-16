# UPDATE
- 18/12/2019, Poslaju changed their tracking website URL and change the way the data is displayed. API fixed and updated.
- 09/01/2018, Poslaju implement SSL in their website, API updated to support SSL by Razlan Abdul Aziz (rizfield)
- As of 29/1/2017, this API has been fixed and can be used as usual. Thanks to Haries Nur Ikhwan (hariesnurikhwan)
- As for 27/1/2017, this API seems not working anymore. This is due to poslaju changing the way the form submit in their tracking website. They are now using jquery to submit the form and append the form data.
- I will try to update this API, but don't expect much for a "hack" or workaround API

# Poslaju Tracking API
- Return JSON formatted string of Poslaju Tracking details
- I have been looking for Poslaju API but I cannot find the one that actually work and easy to use, so, I developed my own.
- Can be use for tracking the Poslaju parcel in your own project/system
- Note:
  - This is not the official API, this is actually just a "hack", or workaround for obtaining the tracking data.
  - This API will fetch data directly from the Poslaju Tracking website, so if there are any problem with the site, this API will also affected.

# Created By
- Afif Zafri
- Date: 16/12/2016
- Updated At: 18/12/2019
- Contact: http://fb.me/afzafri

# Installation
- Drop all files into your server

# Usage
- ```http://site.com/api.php?trackingNo=CODE```
- where ```CODE``` is your parcel tracking number
- It will then return a JSON formatted string, you can parse the JSON string and do what you want with it.

# License
This library is under ```MIT license```, please look at the LICENSE file
