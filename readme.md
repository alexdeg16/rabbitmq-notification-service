Установка:
```
docker-compose build
docker-compose up
docker exec -ti notification_app composer install
docker exec -ti notification_app php bin/console doctrine:database:create
docker exec -ti notification_app php bin/console doctrine:migrations:migrate --no-interaction
```
Запуск слушателя rabbitmq:
```
docker exec -ti notification_app php bin/console rabbitmq:consumer -m 50 notifications
```

Web-панель RabbitMQ: 
```
http://127.0.0.1:15672/
Username: guest
Password: guest
```
Отправление сообщений через rabbitmq:
```json
{
	"type": "OrderPlaced",
	"receiver": "mailtrap@localhost",
	"data": {
		"order_number": 123456,
		"order_total": 123.45,
		"items": [
			{
		   		"product_name": "Flowers 1",
		   		"quantity": 11,
		   		"price": 87,
		   		"price_total": 957
	   		},
			{
		   		"product_name": "Flowers 2",
		   		"quantity": 5,
		   		"price": 35,
		   		"price_total": 175
	   		}
		]
	}
}
```
```json
{
	"type": "EmailConfirmation",
	"receiver": "mailtrap@localhost",
	"data": {
		"activation_link": "http://website.com/activate/5f4dcc3b5aa765d61d8327deb882cf99"
	}
}
```
Mailtrap:
```
http://127.0.0.1:8125/?_task=mail&_mbox=INBOX
Username: mailtrap
Password mailtrap
```
API:
```
http://127.0.0.1/api/notifications/
http://127.0.0.1/api/notifications/?page=1&status=error&subject=subj&orderBy=status&orderDirection=desc
http://127.0.0.1/api/notifications/1
```

