# Poslaju Tracking API
- Return JSON formatted string of Poslaju Tracking details
- I have been looking for Poslaju API but I cannot find the one that actually work and easy to use, so, I developed my own.
- Can be use for tracking the Poslaju parcel in your own project/system
- Note:
  - This is not the official API, this is actually just a "hack", or workaround for obtaining the tracking data.
  - This API will fetch data directly from the Poslaju Tracking website, so if there are any problem with the site, this API will also affected.

# Installation
```composer require afzafri/poslaju-tracking-api:dev-master```

# Usage
- ```http://site.com/api.php?trackingNo=CODE```
- where ```CODE``` is your parcel tracking number
- It will then return a JSON formatted string, you can parse the JSON string and do what you want with it.

# Sample Response
```
{
   "http_code":200,
   "error_msg":"No error",
   "message":"Record Found",
   "data":[
      {
         "date_time":"08 Sep 2020, 02:33:50 PM",
         "process":"Item delivered to JOE",
         "event":"Pos Laju Shah Alam "
      },
      {
         "date_time":"08 Sep 2020, 10:41:26 AM",
         "process":"Item out for delivery ",
         "event":"Pos Laju Shah Alam "
      },
      {
         "date_time":"08 Sep 2020, 07:39:04 AM",
         "process":"Arrive at delivery facility at ",
         "event":"Pos Laju Shah Alam "
      },
      {
         "date_time":"08 Sep 2020, 12:22:58 AM",
         "process":"Consignment dispatch out from Transit Office ",
         "event":"Pusat Mel Nasional"
      },
      {
         "date_time":"07 Sep 2020, 08:53:08 PM",
         "process":"Item processed. ",
         "event":"Pusat Mel Nasional"
      },
      {
         "date_time":"07 Sep 2020, 06:02:00 PM",
         "process":"Item dispatched out ",
         "event":"Pos Laju Rawang (Back End)"
      },
      {
         "date_time":"07 Sep 2020, 03:51:05 PM",
         "process":"Item picked up ",
         "event":"Pos Laju Rawang (Back End)"
      },
      {
         "date_time":"07 Sep 2020, 03:51:04 PM",
         "process":"Item picked up ",
         "event":"Pos Laju Rawang (Back End)"
      }
   ],
   "info":{
      "creator":"Afif Zafri (afzafri)",
      "project_page":"https:\/\/github.com\/afzafri\/Poslaju-Tracking-API",
      "date_updated":"18\/12\/2019"
   }
}
```

# Updates
- 11/11/2020, Poslaju changed their tracking website URL. API fixed and updated.
- 18/12/2019, Poslaju changed their tracking website URL and change the way the data is displayed. API fixed and updated.
- 09/01/2018, Poslaju implement SSL in their website, API updated to support SSL by Razlan Abdul Aziz (rizfield)
- As of 29/1/2017, this API has been fixed and can be used as usual. Thanks to Haries Nur Ikhwan (hariesnurikhwan)
- As for 27/1/2017, this API seems not working anymore. This is due to poslaju changing the way the form submit in their tracking website. They are now using jquery to submit the form and append the form data.
- I will try to update this API, but don't expect much for a "hack" or workaround API

# Created By
- Afif Zafri
- Date: 16/12/2016
- Updated At: 11/11/2020
- Contact: http://fb.me/afzafri

# License
This library is under ```MIT license```, please look at the LICENSE file
