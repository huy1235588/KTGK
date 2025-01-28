import requests
from datetime import datetime
import os

# Định nghĩa GraphQL API endpoint và truy vấn
# API_URL = "https://mail.lehuy.id.vn/graphql"
API_URL = "http://localhost:3001/graphql"
QUERY = """
query {
    products {
        _id
        title
        type
        description
        detail
        price
        discount
        discountStartDate
        discountEndDate
        releaseDate
        rating
        isActive
        headerImage
        screenshots
        videos {
            mp4
            webm
            thumbnail
        }
        developer
        publisher
        platform
        genres
        tags
        features
        systemRequirements {
            win {
                title
                minimum
                recommended
            }
            mac {
                title
                minimum
                recommended
            }
            linux {
                title
                minimum
                recommended
            }
        }
        createdAt
        updatedAt,
    }
}
"""

QUERY_ACHIEVEMENTS = """
query {
    getAchievementList {
        productId
        achievements {
            title
            percent
            description
            image
        }
    }
}
"""

QUERY_LANGUAGES = """
query {
    getLanguagesList {
        productId
        languages {
            language
            interface
            fullAudio
            subtitles
        }
        createdAt
    }
}
"""

# Định nghĩa headers
headers = {"Content-Type": "application/json"}


# Hàm gửi yêu cầu GraphQL
def fetch_graphql_data(api_url, query):
    response = requests.post(api_url, json={"query": query}, headers=headers)
    response.raise_for_status()
    return response.json()


# Hàm chuyển đổi dữ liệu JSON thành các câu lệnh SQL
def json_to_sql(data, output_file):
    genres_list = []  # List genres
    tags_list = []
    features_list = []

    # Process products
    with open(f"{output_file}_product.sql", "w", encoding="utf-8") as file:
        insert_statement = f"INSERT INTO products (id, title, type, description, detail, price, discount, discountStartDate, discountEndDate, releaseDate, rating, isActive, headerImage) VALUES\n"
        value_list = []
        for product in data["data"]["products"]:
            product_id = product["_id"]
            title = product["title"].replace("'", "''")
            ptype = product["type"].replace("'", "''")
            description = product["description"].replace("'", "''")
            # Thêm dấu nháy đơn vào đầu và cuối chuỗi nếu có
            detail = (
                f"'{product['detail'].replace('\'', '\'\'')}'"
                if product["detail"]
                else "NULL"
            )
            price = product["price"]
            discount = product["discount"]
            discountStartDate = (
                f"'{datetime.fromtimestamp(int(product['discountStartDate']) / 1000).strftime('%Y-%m-%d')}'"
                if product["discountStartDate"]
                else "NULL"
            )
            discountEndDate = (
                f"'{datetime.fromtimestamp(int(product['discountEndDate']) / 1000).strftime('%Y-%m-%d')}'"
                if product["discountEndDate"]
                else "NULL"
            )
            if product["releaseDate"]:
                releaseDate = f"'{datetime.fromtimestamp(int(product['releaseDate']) / 1000).strftime('%Y-%m-%d')}'"
            rating = product["rating"]
            isActive = "TRUE" if product["isActive"] else "FALSE"
            headerImage = product["headerImage"].replace("'", "''")

            # Insert product
            value_list.append(
                f"({product_id}, '{title}', '{ptype}', '{description}', {detail}, {price}, {discount}, {discountStartDate}, {discountEndDate}, {releaseDate}, {rating}, {isActive}, '{headerImage}')"
            )
        # Ghi câu lệnh insert vào file
        insert_statement += ",\n".join(value_list) + ";"
        file.write(insert_statement)
        print("Đã ghi câu lệnh SQL vào tệp 'insert_product.sql'")

    # Insert developer in a single statement
    with open(f"{output_file}_developer.sql", "w", encoding="utf-8") as file:
        insert_statement = (
            "INSERT INTO product_developers (product_id, developer) VALUES\n"
        )
        value_list = []
        # Duyệt qua từng sản phẩm, nếu có developer thì thêm vào list
        for product in data["data"]["products"]:
            if product["developer"]:
                product_id = product["_id"]
                # Duyệt qua từng developer của sản phẩm
                for developer in product["developer"]:
                    # Thêm vào list một tuple (product_id, developer)
                    value_list.append(
                        f"({product['_id']}, '{developer.replace('\'', '\'\'')}')"
                    )
        # Ghi list developers vào file
        insert_statement += ",\n".join(value_list) + ";\n"
        file.write(insert_statement)
        print("Đã ghi câu lệnh SQL vào tệp 'insert_developer.sql'")

    # Insert publisher in a single statement
    with open(f"{output_file}_publisher.sql", "w", encoding="utf-8") as file:
        insert_statement = (
            "INSERT INTO product_publishers (product_id, publisher) VALUES\n"
        )
        value_list = []
        # Duyệt qua từng sản phẩm, nếu có publisher thì thêm vào list
        for product in data["data"]["products"]:
            if product["publisher"]:
                # Duyệt qua từng publisher của sản phẩm
                for publisher in product["publisher"]:
                    # Thêm vào list một tuple (product_id, publisher)
                    value_list.append(
                        f"({product['_id']}, '{publisher.replace('\'', '\'\'')}')"
                    )
        # Ghi list publishers vào file
        insert_statement += ",\n".join(value_list) + ";\n"
        # Ghi câu lệnh insert vào file
        file.write(insert_statement)
        print("Đã ghi câu lệnh SQL vào tệp 'insert_publisher.sql'")

    # insert platform in a single statement
    with open(f"{output_file}_platform.sql", "w", encoding="utf-8") as file:
        insert_statement = (
            "INSERT INTO product_platforms (product_id, platform) VALUES\n"
        )
        value_list = []
        # Duyệt qua từng sản phẩm, nếu có platform thì thêm vào list
        for product in data["data"]["products"]:
            if product["platform"]:
                # Duyệt qua từng platform của sản phẩm
                for platform in product["platform"]:
                    # Thêm vào list một tuple (product_id, platform)
                    value_list.append(
                        f"({product['_id']}, '{platform.replace('\'', '\'\'')}')"
                    )
        # Ghi list platforms vào file
        insert_statement += ",\n".join(value_list) + ";\n"
        # Ghi câu lệnh insert vào file
        file.write(insert_statement)
        print("Đã ghi câu lệnh SQL vào tệp 'insert_platform.sql'")

    # Insert genres in a single statement
    with open(f"{output_file}_product_genres.sql", "w", encoding="utf-8") as file:
        insert_statement = "INSERT INTO product_genres (product_id, genre_id) VALUES\n"
        genre_id_map = {}  # Map genre_id với genre_name
        value_list = []
        genre_id_counter = 0

        # Duyệt qua từng sản phẩm, nếu có genres thì thêm vào list
        for product in data["data"]["products"]:
            if product["genres"]:
                # Lấy id của sản phẩm
                product_id = product["_id"]

                # Duyệt qua từng genre của sản phẩm
                for genre in product["genres"]:
                    if genre not in genre_id_map:  # Nếu genre chưa tồn tại trong map
                        genre_id_counter += 1
                        genre_id_map[genre] = genre_id_counter
                        # Thêm vào danh sách thể loại
                        genres_list.append((genre_id_counter, genre))

                    # Lấy id của genre từ map và thêm giá trị vào danh sách
                    genre_id = genre_id_map[genre]
                    value_list.append(f"({product_id}, {genre_id})")

        # Ghi list genres vào file product_genres
        insert_statement += ",\n".join(value_list) + ";\n"
        file.write(insert_statement)
        print("Đã ghi câu lệnh SQL vào tệp 'insert_product_genres.sql'")

        # Ghi list genres vào file genres
        with open(f"{output_file}_genres.sql", "w", encoding="utf-8") as file_genres:
            file_genres.write(f"INSERT INTO genres (id, name) VALUES\n")
            genres_values = ",\n".join(
                [f"({id}, '{name.replace('\'', '\\\'')}')" for id, name in genres_list]
            )
            file_genres.write(genres_values + ";\n")
        print("Đã ghi câu lệnh SQL vào tệp 'insert_genres.sql'")

    # Insert tags in a single statement
    with open(f"{output_file}_product_tags.sql", "w", encoding="utf-8") as file:
        insert_statement = "INSERT INTO product_tags (product_id, tag_id) VALUES\n"
        value_list = []
        tag_map = {}  # Map tag_id với tag_name
        tag_id_counter = 0

        for product in data["data"]["products"]:
            if product["tags"]:
                # Lấy id của sản phẩm
                product_id = product["_id"]

                # Duyệt qua từng tag của sản phẩm
                for tag in product["tags"]:
                    if tag not in tag_map:
                        tag_id_counter += 1
                        tag_map[tag] = tag_id_counter
                        # Thêm vào danh sách tag
                        tags_list.append((tag_id_counter, tag))

                    # Thêm vào danh sách giá trị
                    tag_id = tag_map[tag]
                    value_list.append(f"({product_id}, {tag_id})")

        # Ghi list tags vào file product_tags
        insert_statement += ",\n".join(value_list) + ";\n"
        file.write(insert_statement)
        print("Đã ghi câu lệnh SQL vào tệp 'insert_product_tags.sql'")

        # Ghi list tags vào file tags
        with open(f"{output_file}_tags.sql", "w", encoding="utf-8") as file_tags:
            file_tags.write(f"INSERT INTO tags (id, name) VALUES\n")
            tags_values = ",\n".join(
                [f"({id}, '{name.replace('\'', '\\\'')}')" for id, name in tags_list]
            )
            file_tags.write(tags_values + ";\n")
        print("Đã ghi câu lệnh SQL vào tệp 'insert_tags.sql'")

    # Insert features in a single statement
    with open(f"{output_file}_product_features.sql", "w", encoding="utf-8") as file:
        insert_statement = "INSERT INTO product_features (product_id, feature) VALUES\n"
        value_list = []
        feature_map = {}  # Map feature_id với feature_name
        feature_id_counter = 0

        for product in data["data"]["products"]:
            if product["features"]:
                # Lấy id của sản phẩm
                product_id = product["_id"]

                # Duyệt qua từng feature của sản phẩm
                for feature in product["features"]:
                    if feature not in feature_map:
                        feature_id_counter += 1
                        feature_map[feature] = feature_id_counter
                        # Thêm vào danh sách feature
                        features_list.append((feature_id_counter, feature))

                    # Thêm vào danh sách giá trị
                    feature_id = feature_map[feature]
                    value_list.append(f"({product_id}, {feature_id})")

        # Ghi list features vào file product_features
        insert_statement += ",\n".join(value_list) + ";\n"
        file.write(insert_statement)
        print("Đã ghi câu lệnh SQL vào tệp 'insert_product_features.sql'")

        # Ghi list features vào file features
        with open(
            f"{output_file}_features.sql", "w", encoding="utf-8"
        ) as file_features:
            file_features.write(f"INSERT INTO features (id, name) VALUES\n")
            features_values = ",\n".join(
                [
                    f"({id}, '{name.replace('\'', '\\\'')}')"
                    for id, name in features_list
                ]
            )
            file_features.write(features_values + ";\n")
        print("Đã ghi câu lệnh SQL vào tệp 'insert_features.sql'")

    # Insert systemRequirements in a single statement
    with open(f"{output_file}_systemRequirements.sql", "w", encoding="utf-8") as file:
        insert_statement = "INSERT INTO product_system_requirements (product_id, platform, title, minimum, recommended) VALUES\n"
        value_list = []
        for product in data["data"]["products"]:
            if product["systemRequirements"]:
                for platform, requirements in product["systemRequirements"].items():
                    for requirement in requirements:
                        title = requirement["title"].replace("'", "''")
                        minimum = requirement["minimum"].replace("'", "''")
                        recommended = requirement["recommended"].replace("'", "''")
                        value_list.append(
                            f"({product['_id']}, '{platform}', '{title}', '{minimum}', '{recommended}')"
                        )
        insert_statement += ",\n".join(value_list) + ";\n"
        file.write(insert_statement)
        print("Đã ghi câu lệnh SQL vào tệp 'insert_systemRequirements.sql'")

    # Insert screenshots in a single statement
    with open(f"{output_file}_screenshots.sql", "w", encoding="utf-8") as file:
        insert_statement = (
            "INSERT INTO product_screenshots (product_id, screenshot) VALUES\n"
        )
        value_list = []
        for product in data["data"]["products"]:
            if product["screenshots"]:
                value_list.extend(
                    [
                        f"({product['_id']}, '{screenshot.replace('\'', '\'\'')}')"
                        for screenshot in product["screenshots"]
                    ]
                )
        insert_statement += ",\n".join(value_list) + ";\n"
        file.write(insert_statement)
        print("Đã ghi câu lệnh SQL vào tệp 'insert_screenshots.sql'")

    # Insert videos in a single statement
    with open(f"{output_file}_videos.sql", "w", encoding="utf-8") as file:
        insert_statement = (
            "INSERT INTO product_videos (product_id, mp4, webm, thumbnail) VALUES\n"
        )
        value_list = []
        for product in data["data"]["products"]:
            if product["videos"]:
                value_list.extend(
                    [
                        f"({product['_id']}, '{video['mp4'].replace('\'', '\'\'')}', '{video['webm'].replace('\'', '\'\'')}', '{video['thumbnail'].replace('\'', '\'\'')}')"
                        for video in product["videos"]
                    ]
                )
        insert_statement += ",\n".join(value_list) + ";\n"
        file.write(insert_statement)
        print("Đã ghi câu lệnh SQL vào tệp 'insert_videos.sql'")


# Hàm chuyển đổi dữ liệu achievements thành các câu lệnh SQL
def achievements_to_sql(data, output_file):
    with open(output_file, "w", encoding="utf-8") as file:
        insert_statement = f"INSERT INTO product_achievements (product_id, title, percent, description, image) VALUES"
        value_list = []
        achievement_map = {}  # Map achievement_id với achievement_name
        achievement_id_counter = 0

        for achievement in data["data"]["getAchievementList"]:
            product_id = achievement["productId"]
            for ach in achievement["achievements"]:
                # Lấy title, percent, description, image của achievement
                title = ach["title"].replace("'", "''").replace('\\', '')
                percent = ach["percent"]
                description = ach["description"].replace("'", "''").replace('\\', '')
                
                # steamcommunity/public/images/apps/2440380/faf.jpg -> 2440380/faf.jpg
                image = ach["image"].split("/")[-2] + "/" + ach["image"].split("/")[-1]
                # Nếu achievement chưa tồn tại trong map thì thêm vào
                if title not in achievement_map:
                    achievement_id_counter += 1
                    achievement_map[title] = achievement_id_counter
                    value_list.append(
                        f"({product_id}, '{title}', {percent}, '{description}', '{image}')"
                    )

        # Ghi câu lệnh insert vào file
        insert_statement += ",\n".join(value_list) + ";"
        file.write(insert_statement)
        print("Đã ghi câu lệnh SQL vào tệp 'insert_achievements.sql'")
        

# Hàm chuyển đổi dữ liệu languages thành các câu lệnh SQL
def languages_to_sql(data, output_file):
    languages_list = []  # List languages
    with open(f"{output_file}_product_languages.sql", "w", encoding="utf-8") as file:
        insert_statement = f"INSERT INTO product_languages (product_id, language, interface, fullAudio, subtitles) VALUES"
        value_list = []
        language_map = {}  # Map language_id với language_name
        language_id_counter = 0

        for language in data["data"]["getLanguagesList"]:
            product_id = language["productId"]
            for lang in language["languages"]:
                # Lấy tên ngôn ngữ
                lang_name = lang["language"].replace("'", "''")
                # Nếu language chưa tồn tại trong map thì thêm vào
                if lang_name not in language_map:
                    # Tăng id cho ngôn ngữ
                    language_id_counter += 1
                    # Thêm vào map
                    language_map[lang_name] = language_id_counter
                    # Thêm vào danh sách ngôn ngữ
                    languages_list.append((language_id_counter, lang_name))

                # "True" => 1, "False" => 0
                interface = 1 if lang["interface"] else 0
                fullAudio = 1 if lang["fullAudio"] else 0
                subtitles = 1 if lang["subtitles"] else 0
                # Thêm vào danh sách ngôn ngữ
                languages_id = language_map[lang_name]
                value_list.append(
                    f"({product_id}, '{languages_id}', {interface}, {fullAudio}, {subtitles})"
                )

        # Ghi câu lệnh insert vào file
        insert_statement += ",\n".join(value_list) + ";"
        file.write(insert_statement)
        print("Đã ghi câu lệnh SQL vào tệp 'insert_product_languages.sql'")

        # Ghi list languages vào file languages
        with open(
            f"{output_file}_languages.sql", "w", encoding="utf-8"
        ) as file_languages:
            file_languages.write(f"INSERT INTO languages (id, name) VALUES\n")
            languages_values = ",\n".join(
                [
                    f"({lang_id}, '{lang_name.replace('\'', '\\\'')}')"
                    for lang_id, lang_name in languages_list
                ]
            )
            file_languages.write(languages_values + ";\n")
        print("Đã ghi câu lệnh SQL vào tệp 'insert_languages.sql'")


# Ghi câu lệnh SQL vào file
def write_sql_to_file(sql_statements, filename):
    with open(filename, "w", encoding="utf-8") as file:
        file.write("\n".join(sql_statements))


def insert_user(data, output_file):
    with open(output_file, "w", encoding="utf-8") as file:
        insert_statement = f"INSERT INTO users (id, name, phone, email, gender, username, password, role) VALUES\n"
        value_list = [
            "(1, 'ha', '0123456789', 'ha@ha', 'male', 'ha', 'ha', 'admin')",
            "(3, 'he', '0123456789', 'he@he', 'female', 'he', 'he', 'user')",
            "(4, 'ho', '0123456789', 'ho@ho', 'male', 'ho', 'ho', 'user')",
        ]

        insert_statement += ",\n".join(value_list) + ";"
        file.write(insert_statement)


# Chạy các bước
if __name__ == "__main__":
    try:
        # Lấy dữ liệu từ GraphQL API
        graphql_data = fetch_graphql_data(API_URL, QUERY)
        # Lấy dữ liệu achievements từ GraphQL API
        achievements_data = fetch_graphql_data(API_URL, QUERY_ACHIEVEMENTS)
        # Lấy dữ liệu languages từ GraphQL API
        languages_data = fetch_graphql_data(API_URL, QUERY_LANGUAGES)

        # Kiểm tra xem có folder database chưa, nếu chưa thì tạo mới
        if not os.path.exists("database"):
            os.makedirs("database")

        # Chuyển đổi JSON thành các câu lệnh SQL
        sql_statements = json_to_sql(graphql_data, "database/insert")

        # Chuyển đổi JSON thành các câu lệnh SQL
        sql_statements = achievements_to_sql(
            achievements_data, "database/insert_achievements.sql"
        )
        print("Đã ghi câu lệnh SQL vào tệp 'insert_achievements.sql'")

        # Chuyển đổi JSON thành các câu lệnh SQL
        sql_statements = languages_to_sql(languages_data, "database/insert")

        # insert user
        insert_user(graphql_data, "database/insert_user.sql")
        print("Đã ghi câu lệnh SQL vào tệp 'insert_user.sql'")

    except Exception as e:
        print(f"Có lỗi xảy ra: {e}")
