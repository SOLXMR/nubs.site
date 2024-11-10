import sys
import json
import matplotlib.pyplot as plt
import datetime

# Paths to save the chart and log files
save_path = '/home/nubsmrbf/public_html/bot/solana_chart.png'  # Update with your cPanel username
log_path = '/home/nubsmrbf/public_html/bot/chart_log.txt'

def log_message(message):
    with open(log_path, 'a') as log_file:
        log_file.write(f"{datetime.datetime.now()} - {message}\n")

def generate_chart(data):
    try:
        timestamps = [datetime.datetime.fromtimestamp(point[0] / 1000) for point in data["prices"]]
        prices = [point[1] for point in data["prices"]]

        plt.figure(figsize=(10, 5))
        plt.plot(timestamps, prices, label="Solana Price (USD)")
        plt.title("Solana Market Chart (Last 24 Hours)")
        plt.xlabel("Time")
        plt.ylabel("Price in USD")
        plt.legend()
        plt.grid(True)

        # Save the chart image in the bot directory
        plt.savefig(save_path)
        plt.close()
        log_message(f"Chart successfully saved at {save_path}")
    except Exception as e:
        log_message(f"Error generating chart: {e}")

if __name__ == "__main__":
    try:
        data = json.loads(sys.argv[1])
        generate_chart(data)
    except Exception as e:
        log_message(f"Error parsing data: {e}")
