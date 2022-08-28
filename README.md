# Vamo Doc de instalação

Release: 27/05/2021 at 02:21am

* Fatores para instalação
- Instale o Xampp 7.4.15 (Caso seu PHP seja maior que 7.4.15)
- Crie um banco de dados no mysql PHPAdmin
- Importe o banco de dados

**No VSCODE**

- abra a pasta do Painel

**Terminal**

- Abra o terminal na pasta do projeto utilizando o VSCODE
- Digite o comando abaixo:
* composer install --ignore-platform-reqs

Após isso, rode php artisan serve e o painel já irá está no IP informado pelo terminal



**Criado por**
 - VICTOR LIMA
 
```java 

@FormUrlEncoded
    
@POST("api/user/checkversion")
Observable<CheckVersion> checkversion(@FieldMap HashMap<String, Object> params);

@FormUrlEncoded
@POST("api/provider/oauth/token")
Observable<User> login(@FieldMap HashMap<String, Object> params);

@FormUrlEncoded
@POST("api/provider/oauth/token")
Observable<User> refreshToken();

@FormUrlEncoded
@POST("api/provider/auth/google")
Observable<Token> loginGoogle(@FieldMap HashMap<String, Object> params);

@FormUrlEncoded
@POST("api/provider/auth/facebook")
Observable<Token> loginFacebook(@FieldMap HashMap<String, Object> params);

@GET("https://maps.googleapis.com/maps/api/geocode/json?latlng=8.403521,124.590141&sensor=true&key=")
Observable<Object> getPlaces();

@Multipart
@POST("api/provider/register")
Observable<User> register(@PartMap Map<String, RequestBody> params, @Part List<MultipartBody.Part> file);

@FormUrlEncoded
@POST("api/provider/verify")
Observable<Object> verifyEmail(@Field("email") String email);

@GET("api/provider/profile")
Observable<UserResponse> getProfile();

@Multipart
@POST("api/provider/profile")
Observable<UserResponse> profileUpdate(@PartMap Map<String, RequestBody> params, @Part MultipartBody.Part file);

@FormUrlEncoded
@POST("api/provider/logout")
Observable<Object> logout(@FieldMap HashMap<String, Object> params);

@GET("api/provider/trip?")
Observable<TripResponse> getTrip(@QueryMap HashMap<String, Object> params);

@FormUrlEncoded
@POST("api/provider/profile/available")
Observable<Object> providerAvailable(@FieldMap HashMap<String, Object> params);

@FormUrlEncoded
@POST("api/provider/forgot/password")
Observable<ForgotResponse> forgotPassword(@FieldMap HashMap<String, Object> params);

@FormUrlEncoded
@POST("api/provider/reset/password")
Observable<Object> resetPassword(@FieldMap HashMap<String, Object> params);

@FormUrlEncoded
@POST("api/provider/profile/password")
Observable<Object> changePassword(@FieldMap HashMap<String, Object> params);

@FormUrlEncoded
@POST("api/provider/trip/{request_id}")
Observable<Object> acceptRequest(@Field("dummy") String dummy, @Path("request_id") Integer request_id);

@DELETE("api/provider/trip/{request_id}")
Observable<Object> rejectRequest(@Path("request_id") Integer request_id);

@FormUrlEncoded
@POST("api/provider/cancel")
Observable<Object> cancelRequest(@FieldMap HashMap<String, Object> params);

@FormUrlEncoded
@POST("api/provider/trip/{request_id}")
Observable<Object> updateRequest(@FieldMap HashMap<String, Object> params,
                                    @Path("request_id") Integer request_id);

@FormUrlEncoded
@POST("api/provider/trip/{request_id}/rate")
Observable<Rating> ratingRequest(@FieldMap HashMap<String, Object> params, @Path("request_id") Integer request_id);

@GET("api/provider/requests/history")
Observable<List<HistoryList>> getHistory();

@GET("api/provider/requests/history/details?")
Observable<HistoryDetail> getHistoryDetail(@Query("request_id") String request_id);

@GET("api/provider/requests/upcoming")
Observable<List<HistoryList>> getUpcoming();

@GET("api/provider/requests/upcoming/details?")
Observable<HistoryDetail> getUpcomingDetail(@Query("request_id") String request_id);

@DELETE("api/provider/logout/{user_id}")
Observable<Object> logout(@Path("user_id") Integer user_id);

@GET("api/provider/target")
Observable<EarningsList> getEarnings();

@FormUrlEncoded
@POST("api/provider/summary")
Observable<Summary> getSummary(@Field("data") String data);

@GET("api/provider/help")
Observable<Help> getHelp();

@GET("/api/provider/wallettransaction")
Observable<WalletResponse> getWalletTransactions();

@FormUrlEncoded
@POST("/api/provider/add/money")
Observable<WalletMoneyAddedResponse> addMoney(@FieldMap HashMap<String, Object> obj);

@GET("api/provider/transferlist")
Observable<RequestDataResponse> getRequestAmtData();

@FormUrlEncoded
@POST("/api/provider/requestamount")
Observable<Object> postRequestAmt(@Field("amount") double amount, @Field("type") String type);

@GET("/api/provider/requestcancel?")
Observable<Object> getRemoveRequestAmt(@Query("id") int id);

//    @Headers({"Content-Type: application/json", "Authorization: key=" + BuildConfig.FCM_SERRVER_KEY})
//    @POST("fcm/send")
//    Observable<Object> sendFcm(@Body JsonObject jsonObject);

@FormUrlEncoded
@POST("/api/provider/chat")
Observable<Object> postChatItem(
        @Field("sender") String sender,
        @Field("user_id") String user_id,
        @Field("message") String message);

@FormUrlEncoded
@POST("/api/provider/profile/language")
Observable<Object> postChangeLanguage(@Field("language") String language);

@GET("/api/provider/profile/documents")
Observable<DriverDocumentResponse> getDriverDocuments();

@Multipart
@POST("api/provider/profile/documents/store")
Observable<DriverDocumentResponse> postUploadDocuments(@PartMap Map<String, RequestBody> params,
                                                        @Part List<MultipartBody.Part> file);

@FormUrlEncoded
@POST("api/provider/providercard/destroy")
Observable<Object> deleteCard(@Field("card_id") String cardId,
                                @Field("_method") String method);

@GET("api/provider/providercard")
Observable<List<Card>> card();

@FormUrlEncoded
@POST("api/provider/providercard")
Observable<Object> addcard(@Field("stripe_token") String stripeToken);

@FormUrlEncoded
@POST("api/provider/providercard/update")
Observable<Object> changeCard(@Field("card_id") String cardId,
                                @Field("_method") String method);

@GET("/api/provider/settings")
Observable<SettingsResponse> getSettings();

@FormUrlEncoded
@POST("api/provider/verify-credentials")
Observable<Object> verifyCredentials(@Field("country_code") String countryCode, @Field("mobile") String mobile);

@GET("api/provider/reasons")
Observable<List<CancelResponse>> getCancelReasons();

@FormUrlEncoded
@POST("api/provider/dispute")
Observable<Object> dispute(@FieldMap HashMap<String, Object> params);

//    1 for start and 0 or end
@FormUrlEncoded
@POST("api/provider/waiting")
Observable<TimerResponse> waitingTime(@Field("status") String status,
                                        @Field("id") String requestId);

//    1 for start and 0 or end
@FormUrlEncoded
@POST("api/provider/waiting")
Observable<TimerResponse> CheckWaitingTime(@Field("id") String requestId);

@GET("/api/provider/notifications/provider")
Observable<List<NotificationManager>> getNotificationManager();

@POST("api/provider/requests/instant/ride")
Observable<TripResponse> requestInstantRide(@QueryMap Map<String, Object> params);

@GET("api/user/estimated/fare_without_auth")
Observable<EstimateFare> estimateFare(@QueryMap Map<String, Object> params);

@FormUrlEncoded
@POST("api/provider/dispute-list")
Observable<List<DisputeResponse>> getDispute(@Field("dispute_type")String dispute_type);

@FormUrlEncoded
@POST("api/provider/drop-item")
Observable<Object> dropItem(@FieldMap HashMap<String, Object> params);

@GET("api/user/states")
Observable<List<State>> getStates();

@GET("/api/user/cities")
Observable<List<City>> getCities(@Query("state_id") int stateId);

@GET("/api/provider/settings")
Observable<SettingsResponse> getServices(@Query("city_id") int cityId);

```