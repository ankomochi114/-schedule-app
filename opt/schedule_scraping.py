import re
import copy
import time
import os
import sys
import requests
import csv
import locale
import pandas as pd
import mysql.connector
from mysql.connector import errorcode
from datetime import datetime as dt
from bs4 import BeautifulSoup
from selenium import webdriver
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.common.desired_capabilities import DesiredCapabilities
from selenium.common.exceptions import TimeoutException

locale.setlocale(locale.LC_TIME, "ja_JP.UTF-8")

localPath = "/root/opt/"

## ドライバー設定
def web_driver():
    options = webdriver.ChromeOptions()
    options.add_argument("--headless")
    options.add_argument("--no-sandbox")
    options.add_argument("start-maximized")
    options.add_argument("disable-infobars")
    options.add_argument("--disable-extensions")
    options.add_argument("--disable-gpu")
    options.add_argument("--disable-dev-shm-usage")

    driver = webdriver.Remote(
        command_executor="http://selenium:4444/wd/hub",
        options=options,
    )

    return driver


## 公式HPの巡回
def theater_scraping(driver, theater_name, theater_url):
    scraping_html = []
    driver.get(theater_url)
    # スクレイピング
    try:
        time.sleep(15)
        if "ヨシモト∞ドームⅡ" == theater_name:
            driver.find_element(
                By.CSS_SELECTOR, ".schedule-venue li:nth-child(2) a"
            ).click()
            time.sleep(2)

        WebDriverWait(driver, 15).until(
            EC.presence_of_element_located((By.CLASS_NAME, "schedule-block"))
        )

        # 当月
        scraping_html.append(driver.page_source)
        # １ヶ月後
        driver.find_element(
            By.CSS_SELECTOR, ".calendar-month li:nth-child(2) a"
        ).click()
        time.sleep(2)
        scraping_html.append(driver.page_source)
        # ２ヶ月後
        driver.find_element(
            By.CSS_SELECTOR, ".calendar-month li:nth-child(3) a"
        ).click()
        time.sleep(2)
        scraping_html.append(driver.page_source)

        return scraping_html
    except:
        return ""


## db接続
def db_connect():
    connection = None

    connection = mysql.connector.connect(
        host="db", user="docker", passwd="docker", db="schedule"
    )

    return connection


## データベースから情報を取得
def db(select, table):

    try:
        connection = db_connect()
        cursor = connection.cursor()

        sql = f"""
            SELECT  {select}
            FROM    {table}
        """
        cursor.execute(sql)

        ans = cursor.fetchall()
        cursor.close()
        return ans

    except Exception as e:
        print(f"Error Occurred: {e}")

    finally:
        if connection is not None and connection.is_connected():
            connection.close()


## データベースにスケジュールを登録
def db_schedule_add(data, target_id):
    try:
        connection = db_connect()
        cursor = connection.cursor()

        ## データがあるか確認する
        sql = "SELECT * FROM schedules WHERE date= %s AND start_time= %s AND stage_id = %s"
        param = (data[1], data[3], data[6])
        cursor.execute(sql, param)
        ans = cursor.fetchone()

        ## データがない場合追加する
        if not ans:
            sql = """
                INSERT INTO schedules
                    (title, date, venue_time, start_time, end_time, description, stage_id, created_at, updated_at)
                VALUES 
                    (%s, %s, %s, %s, %s, %s, %s, %s, %s)
            """

            createTime = dt.today().strftime("%Y-%m-%d %H:%M:%S")
            data.append(createTime)
            data.append(createTime)
            data = tuple(data)

            cursor.execute(sql, data)
            cursor.execute("SELECT last_insert_id()")
            ans = cursor.fetchone()
            sql = """
                INSERT INTO player_schedule 
                    (player_id, schedule_id)
                VALUES 
                    (%s, %s)
            """
            param = (target_id, ans[0])
            cursor.execute(sql, param)
            connection.commit()
            cursor.close()
        else:
            ## 出演者のみ追加で登録する場合
            # sql = (
            #     "SELECT * FROM player_schedule WHERE player_id= %s AND schedule_id= %s"
            # )
            # param = (target_id, ans[0])
            # cursor.execute(sql, param)
            # data = cursor.fetchone()

            sql = """
                INSERT INTO player_schedule 
                    (player_id, schedule_id)
                VALUES 
                    (%s, %s)
            """
            param = (target_id, ans[0])
            cursor.execute(sql, param)
            connection.commit()
            cursor.close()

            # if not data:
            #     sql = """
            #         INSERT INTO player_schedule 
            #             (player_id, schedule_id)
            #         VALUES 
            #             (%s, %s)
            #     """
            #     param = (target_id, data[0])
            #     print(param)
            #     cursor.execute(sql, param)
            #     connection.commit()
            # else:
            #     print(data)

            ## データがあり、変更点がある場合は更新する

    except Exception as e:
        print(f"Error Occurred: {e}")

    finally:
        if connection is not None and connection.is_connected():
            connection.close()


## htmlを加工
def parse(soup, target_cast, theater_id):
    count = 0
    result = []
    # 特定の出演者が出ているライブを抽出する
    for live_news in soup.find_all(class_="schedule-block"):
        date = re.search(r"[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}", live_news.get("id"))

        # ライブの概要を取得
        for live_item_summary in live_news.find_all(class_="schedule-time"):
            if live_item_summary.find("span", class_="bold") is not None:
                time = re.search(
                    r"開場[0-9]{1,2}:[0-9]{1,2}",
                    live_item_summary.find("span", class_="bold").get_text(),
                )

                if time is not None:
                    venue_time = time.group()
                    venue_time = venue_time.replace("開場", "")
                else:
                    venue_time = None

                time = re.search(
                    r"開演([0-9]{1,2}:[0-9]{1,2})",
                    live_item_summary.find("span", class_="bold").get_text(),
                )
                if time is not None:
                    start_time = time.group()
                    start_time = start_time.replace("開演", "")
                else:
                    start_time = None

                time = re.search(
                    r"終演[0-9]{1,2}:[0-9]{1,2}",
                    live_item_summary.find("span", class_="bold").get_text(),
                )
                if time is not None:
                    end_time = time.group()
                    end_time = end_time.replace("終演", "")
                else:
                    end_time = None
            else:
                venue_time = None
                start_time = None
                end_time = None

            if target_cast in live_item_summary.text:
                result.append(
                    [
                        # title
                        live_item_summary.find("strong").get_text(),
                        # date
                        date.group(),
                        # venue_time
                        venue_time,
                        # start_time
                        start_time,
                        # end_time
                        end_time,
                        # stage_id
                        theater_id,
                    ]
                )

        # ライブの詳細を取得
        for live_item_detail in live_news.find_all(class_="schedule-detail"):
            for live_item_detail_menber in live_item_detail.find_all(
                class_="schedule-detail-member"
            ):
                if target_cast in live_item_detail_menber.text:
                    result[count].insert(5, live_item_detail_menber.text)
                    count += 1
    return result


## メイン処理
def main(theater_list, target_list):
    driver = web_driver()

    # 芸人ごとに処理を繰り返す。
    for target in target_list:
        target_id, target_cast = target[0], target[1]

        # 劇場ごとに繰り返す
        for theater in theater_list:
            theater_id, theater_name, theater_url = theater[0], theater[1], theater[2]

            # スケジュールページを巡回
            html_list = ""
            html_list = theater_scraping(driver, theater_name, theater_url)

            # 月毎に繰り返す
            for html in html_list:
                # 一月分のデータを格納する
                result = parse(
                    BeautifulSoup(html, "html.parser"), target_cast, theater_id
                )

                # データを登録
                for data in result:
                    db_schedule_add(data, target_id)

    driver.quit()


theater_list = db("id, name, url", "stages")
target_list = db("id, name", "players")

main(theater_list, target_list)
