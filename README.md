# yj_nfc-tag-shop-partnerlink
[NFC-tag-shop.de](https://www.nfc-tag-shop.de?nfcPal=pocketPC) changed its partner link as of today, april 5th 2022.
I had to convert all old links from `sPartner=code` to `nfcPal=code`.

- therefore I used (again) a single php file to convert the links within the mariaDB of the wordpress installation.
- the script dumps a csv-file to document all the changes made.
- by default, only 100 appearances of the corresponding links are converted in one run of the PHP script. You may change that within the PHP onbviously if you feel comfortable. I decided 100 would be fair enough to be able to closely watch the conversion and check for mistakes or false positives. So far, I saw none when converting about 2.3k links on our Wordpress installation at [PocketPC.ch](https://www.pocketpc.ch/magazin).

# == NO WARRANTY ==
obviously, but still to mention: This code comes with no warranty what so ever. I saw no issues and have not run into any problems, but you are solely responsible for what you do on your wordpress. Don't blame me if you messed it up!

# == Usage Instructions ==
- **MAKE A BACKUP OF YOUR WORDPRESS, ESPECIALLY THE DB!**
- upload to your server (or a place that has access to the sql server).
  But **NOT, NEVER EVER** in a folder accessible from the outside world (e.g. htdocs). It is solely for you to use them via command line, not via browser! I would recommend your user's home folder: `cd ~;git clone https://github.com/yjeanrenaud/yj_nfc-tag-shop-partnerlink; cd yj_nfc-tag-shop-partnerlink`
- enter your **db credentials** and config in the php files (db host, username, password, db name)
- connect to the server via ssh (or terminal or whatever) or use your console if you run it locally
- run `php -f convert.php` and
  - check the output on your terminal. check the `yjsqlSIMULATE.csv` if everything looks ok
  - change `$doChange=false;` on line *15* to `$doChange=true` when you are willing to commit changes to your db. remove `yjsqlSIMULATE.csv` if you whish.
  - run the script again and repeat as long as there are still hits. Check  `yjsqlbackup.csv` to be sure.
- remove convert.php file from your server. It contains your ** db credentilas as plain text** and therefore is a **severe security threat**! I would remove the csv-file, too.
