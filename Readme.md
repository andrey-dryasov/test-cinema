Run `make install`


For posters generation run `make generate-movie-posters`

For checkin API token system:
1. create new `user` in `User table`
2. add `username` and `token` for user
3. in headers set `X-AUTH-TOKEN` = `user token` 