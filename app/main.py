from flask import Flask, jsonify

app = Flask(__name__)

@app.route('/', methods=['GET'])
def read_root():
    return jsonify({"message": "API is running!"})

if __name__ == '__main__':
    app.run(debug=True)
