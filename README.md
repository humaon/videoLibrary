# PREREQUISITES
- `PHP >= 7.1.3.`
- `Composer is installed on your computer (version >= 1.3.2).`


# ClONE THE APPLICATION

```
You can download the repository or you can clone it via below command-
$ git clone https://github.com/humaon/videoLibrary.git
After downloading run the command-
$ composer install
create a .env file

```
# CREATE THE DATABASE

Open the .env file in your application directory and change the following section:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```
# RUN MIGRATION


```
$ php artisan migrate
```

# USE THE VIDEO LIBRARY
```
Now you are ready to use the library.First of all you have to 
register as a user to add video to the library.You can register 
via hit that post route-
{base_url}/api/register
You have to provide these fields as a from data for registration-

name
email
password
password_confirmation
photo_url(optional)

After successfully register you will get a token which is authorization token
the token will be need for further request.You have to set the authorization token 
in the header section like this-

Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJod
HRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6M
U1NjM5Mzc2MiwiZXhwIjoxNTU2Mzk3MzYyLCJuYmYiOjE1NTYzOTM3Nj
IsImp0aSI6Im9scDVmWkZMSmdFSGp1Z2MiLCJzdWIiOjEsInBydiI6Ijg
3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.
mZQHJVIzgeBCf4kykQ34bfR2rSEcmtSiQGk8modcrd4

You can also login by providing the email and password by hitting the 
{base_url}/api/login url via a POST method and after login you 
will get the token also


Then You can add video by hitting that POST route-

{base_url}/api/videos
remember the route should be in POST mehtod to add video and 
it requires following data
1)url
2)title
3)description (optional)
4)thumbnail_url (optional)

After adding videos you can see all the videos via GET method of this url
{base_url}/api/videos 
it is should be GET method you can see all the videos associated with like_count.

Now in the next you can like a video by hitting this Get route 
{base_url}/api/like_video/{video_id}
if you already liked the video it will unlike it.

You can also comment on the video by hitting this POST route

{base_url}/api/comment_on_video/{video_id}
comment field should be fillable

You can see a single video associated with the users who 
liked it and associated comments with user data and also the 
boolean value of curent user liked it or not by visiting this GET route

{base_url}/api/videos/{video_id}

You can also delete and update a video

for delete you have To hit {base_url}/api/videos/{video_id} 
This route in DELETE method and for update you have to hit
{base_url}/api/videos/{video_id} this url in a POST mehtod

```
