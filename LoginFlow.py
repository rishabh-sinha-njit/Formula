import pika
import webbrowser
import os

# RabbitMQ connection details
RABBITMQ_HOST = 'localhost'
RABBITMQ_PORT = 5672
RABBITMQ_QUEUE = 'user_logged_in'

def send_login_message(username):
    connection = pika.BlockingConnection(pika.ConnectionParameters(host=RABBITMQ_HOST))
    channel = connection.channel()

    channel.queue_declare(queue=RABBITMQ_QUEUE)
    channel.basic_publish(exchange='', routing_key=RABBITMQ_QUEUE, body=username)

    print(f"Sent login message for user: {username}")
    connection.close()

def consume_login_message():
    connection = pika.BlockingConnection(pika.ConnectionParameters(host=RABBITMQ_HOST))
    channel = connection.channel()

    channel.queue_declare(queue=RABBITMQ_QUEUE)

    def callback(ch, method, properties, body):
        username = body.decode()
        print(f"Received login message for user: {username}")
        # Fetch user data and render home page
        file_path = os.path.abspath("recalls5.html")
        webbrowser.open(f"file://{file_path}")

    channel.basic_consume(queue=RABBITMQ_QUEUE, on_message_callback=callback, auto_ack=True)

    print(" [*] Waiting for messages. To exit press CTRL+C")
    channel.start_consuming()

if __name__ == '__main__':
	username = "rs2268"
	send_login_message(username)
