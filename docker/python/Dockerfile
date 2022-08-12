FROM python:3.9-buster

RUN apt-get update
RUN apt-get -y install locales && \
    localedef -f UTF-8 -i ja_JP ja_JP.UTF-8
ENV LANG ja_JP.UTF-8
ENV LANGUAGE ja_JP:ja
ENV LC_ALL ja_JP.UTF-8
ENV TZ JST-9
ENV TERM xterm

RUN apt-get install -y vim less
RUN pip install --upgrade pip
RUN pip install --upgrade setuptools

# フォマッター
RUN pip install black

# ライブラリ
RUN pip install mysql-connector-python
RUN pip install selenium==4.0.0b4
RUN pip install requests
RUN pip install beautifulsoup4
RUN pip install pandas
