import pika

rabbitmq_nodes = ['10.242.179.206', '10.242.180.20', '10.242.46.127', '10.242.230.35']

credentials = pika.PlainCredentials(username='admin', password='password')

virtual_host = '/'
port = 5672

for node in rabbitmq_nodes:
    print(f"Trying to connect to RabbitMQ node: {node}")
    connection_params = pika.ConnectionParameters(
        host=node,
        port=port,
        virtual_host=virtual_host,
        credentials=credentials
    )
    try:
        connection = pika.BlockingConnection(connection_params)
        print(f"Successfully connected to RabbitMQ node: {node}")
        break
    except pika.exceptions.AMQPConnectionError as e:
        print(f"Failed to connect to RabbitMQ node {node}: {e}")
else:
    print("Unable to connect to any RabbitMQ node.")
    exit(1)  


channel = connection.channel()

channel.queue_declare(queue='fetobequeue', durable=True)
channel.queue_declare(queue='betofequeue', durable=True)

print("Queues 'fetobequeue' and 'betofequeue' declared successfully.")


