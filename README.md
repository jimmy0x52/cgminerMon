Useful? I accept tips:

BTC: 12TVzXjhRzv8PY7Hmfn5f2A7QKeYQaHFz9

Doge: DBogcnz5fNpAePHc2qbj9Z2KxvBzoaRhm7

SCREENSHOT: http://i.imgur.com/XbB9fBA.png

One more thing before we get into it:

This uses Amazon S3's PHP SDK. It's located in /aws and /remote_monitor/aws

Feel free to delete this and download a fresh copy from amazon if you're nervous about it. It's unedited from Amazon.

WHAT IS CGMINERMON
===================================================
cgminerMon is a web-based monitoring dashboard for watching your cgminers. It requires no modification to existing firewall rules, special network configuration, or otherwise risky behavior with your precious cgminer instances.

Utilizing Amazon S3 - cgminer exchanges data with datafiles that are uploaded and downloaded to S3. This allows you to have an unlimited number of miners uploading data, and an unlimited number of dashboards with access to this data. None of the dashboards have to have access to your miners, and none of your miners have access to your dashboards.

Example:

Mom and Dad don't use their computer much. You can setup cgminer on their machine to run overnight. When it's running, you can have their machine upload monitor data to your S3 bucket. Then, you can monitor it all without ever needing to touch a router or port or VPN.

Another example:

You're mining with a bunch of friends, and you all want to watch eachother's data. You can install remote_monitor on everyone machine to upload to a shared bucket, and then all install the dashboard to monitor this bucket. No one has access to anyone's credentials, hardware or passwords but you can have fun watching eachother's hashrates and mined doge!

SETTING UP YOUR DASHBOARD
===================================================
You'll need an apache webserver with php installed. Check this entire project to the webserver, delete remote_monitor, follow the below instructions to configure includes/config.php, and load up the URL. 

The dashboard will do the following every time you reload the page:

- Update market data in the dropdown tab at the top
- Update your total doges
- Update your miner data from Amazon S3

In addition, the dashboard will refresh just your miner data from Amazon S3 every 5 minutes without reloading the page 

SETTING UP YOUR DATA EXCHANGE
===================================================
cgminerMon is unique in that it requires no direct connection between miner and dashboard. It uses an intermediary to house the data and periodically retreives it to be displayed.

To setup your intermediary, you'll need:

- And Amazon S3 account

To setup your bucket:

- Create a new bucket and give it a unique name. Put this name in /inclues/config.php under AWS_BUCKET_NAME
- Enter the S3 bucket browser, click on your new bucket. Click on the 'Properties' tab on the right side, and then click on 'Permissions'. Add list and read/write access as a new permission for 'Authenticated Users'.
- Next, navigate to the Amazon IAM user manager (https://console.aws.amazon.com/iam/home?#users)
- Create a new user. Make sure 'Generate an access key for each user' is checked
- On the next window, click 'Show Security Credentials'. Copy these into the AWS_ACCESSKEYID and AWS_SECRET in includes/config.php
- Close the confirmation window. Click on your user and click on the 'Permissions' tab
- Click Attach User Policy / Custom Policy / Select
- Give your policy a name (it doesn't matter) and then paste the policy below. This allows this user to access your newly created bucket. (Note: replace BUCKET_NAME in the below with your bucket name from the first step.)

```
{
  "Statement": [
    {
      "Sid": "AllowUserToListRootLevelFilesInASingleBucket",
      "Action": [ "s3:ListBucket", "s3:GetBucketLocation" ],
      "Effect": "Allow",
      "Resource": [ "arn:aws:s3:::BUCKET_NAME"],
      "Condition":{ 
            "StringEquals":{
                    "s3:prefix":[""], 
                    "s3:delimiter":["/"]
             }
       }  
    },
    {
      "Sid": "AllowUserToCreateFilesInSingleBucket",
      "Action": [ "s3:PutObject", "s3:GetObject", "s3:DeleteObject" ],
      "Effect": "Allow",
      "Resource": [ "arn:aws:s3:::BUCKET_NAME/*"  ]
    }
  ]
}  
```

SETTING UP YOUR MINERS
===================================================
First, you'll need to allow API access for any miner. To do that, add the following to your cgminer.conf:

```
"api-port" : "4001",
"api-listen" : true,
"api-allow" : "127.0.0.1"
```

Next, you'll need to setup the monitor to upload miner data from your miners. To do this, You'll need to copy the remote_monitor/ folder to your miner. Edit config.php and setup your variables/access keys for Amazon S3. Once this is complete, you'll have to setup a cron or scheduled task to run monitor.php on a regular schedule (I'd suggest at least every 5-10 minutes). 

Each time it's executed, it will gather all the API data from your miner and upload it to your S3 bucket under the filename of MINERNAME.data (where MINERNAME is the name specified at the top of the remote_monitor/config.php file).


CREDITS
===================================================

This is based upon the miner developed by p4xil described here:

https://bitcointalk.org/index.php?topic=222632

This also uses dogechain.info for your wallet ballance and btc-e and cryptsy for market pricing.