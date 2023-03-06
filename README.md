# community_store_sendy_subscribing
Subscribe Community Store customers to Sendy lists based on products purchased.

## Setup
Install Community Store First.
Have a functional Sendy Instance running. For information on Sendy and how to use it, visit https://sendy.co

Download a 'release' zip of the add-on, unzip this to the packages folder of your concrete5 install (alongside the community_store folder) and install via the dashboard.

To install necessary dependencies, run 'composer install' in the root directory of the add-on.

A new dashboard page is created under Store->Sendy Subscribing where a Sendy API key, installation URL and optional default list ID can be added along with a checkbox to enable product list subscriptions.

To comply with GDPR rules, you can add a checkbox attribute with 'sendy_checkout_subscribe' as attribute handle and add it to the 'Other Customer Choices' Group. If this attribue exists, the customers data will only be added to a sendy list, if the checkbox is checked at checkout.

To add customers to specific Sendy lists for specific products, create a text product attribute with the handle 'sendy_list_id', then enter a list ID when editing a product. A list ID is found under the Sendy settings, list name and defaults section of a list.
