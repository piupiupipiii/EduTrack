FROM python:3.11-slim

WORKDIR /app

COPY ./app /app

RUN pip install --upgrade pip
RUN pip install -r requirements.txt

ENV GOOGLE_APPLICATION_CREDENTIALS=service-account.json
ENV FLASK_APP=main.py
ENV FLASK_RUN_HOST=0.0.0.0
ENV FLASK_RUN_PORT=5000
ENV FLASK_ENV=production

EXPOSE 5000

CMD ["flask", "run"]
