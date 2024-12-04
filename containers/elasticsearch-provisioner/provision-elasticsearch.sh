#!/bin/bash

# Exit script wit ERRORLEVEL if any command fails
set -e

echo "Provisioning elastic search";
echo "Waiting for elastic search to come online..."
./wait-for.sh $1:9200 --timeout=120 -- echo "Elastic search is up and running"

echo "Putting mappings"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/meton-search-activity -d @./schema/meton-search-activity_object.json --header "Content-Type: application/json"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/meton-search-object-image -d @./schema/meton-search-user_group.json --header "Content-Type: application/json"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/meton-search-object-video -d @./schema/meton-search-user_group.json --header "Content-Type: application/json"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/meton-search-object-blog -d @./schema/meton-search-user_group.json --header "Content-Type: application/json"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/meton-search-user -d @./schema/meton-search-user_group.json --header "Content-Type: application/json"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/meton-search-group -d @./schema/meton-search-user_group.json --header "Content-Type: application/json"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/meton-views -d @./schema/meton-views.json --header "Content-Type: application/json"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/meton-boost -d @./schema/meton-boost.json --header "Content-Type: application/json"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/meton-offchain -d @./schema/meton-offchain.json --header "Content-Type: application/json"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/meton-transactions-onchain -d @./schema/meton-transactions-onchain.json --header "Content-Type: application/json"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/meton-graph-subscriptions -d @./schema/meton-graph-subscriptions.json --header "Content-Type: application/json"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/meton-graph-pass -d @./schema/meton-graph-pass.json --header "Content-Type: application/json"
curl -s --write-out ' Status: %{http_code}\n' -X PUT http://$1:9200/meton-comments -d @./schema/meton-comments.json --header "Content-Type: application/json"

echo "elastic search is ready!"
