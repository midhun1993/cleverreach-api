# CleverReach API v2

This is a simple client library for CleverReach API. now it only contain the method for add receivers to news letter groups.
```
$client = CleverReachClient::create(['client_id' => '188xxx', 'login' => 'xxxhun@revolve314.com', 'password' => 'xxx2hgPI' ]);
$client->authenticate();
$response = $client->addReceiver('subscription_group_id',['email'=>"user@revolve314.com","deactivated"=>"0"]);
echo '<pre>';
print_r($response);
echo '</pre>';

```
