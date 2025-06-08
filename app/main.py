from fastapi import FastAPI
from . import db

app = FastAPI()

@app.get("/")
def read_root():
    return {"message": "API is running!"}
