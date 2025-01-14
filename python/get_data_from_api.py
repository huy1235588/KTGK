import requests
from datetime import datetime

# Định nghĩa GraphQL API endpoint và truy vấn
API_URL = "https://mail.lehuy.id.vn/graphql"
QUERY = """
query {
    products {
        _id
        title
        type
        description
        price
        discount
        discountStartDate
        discountEndDate
        releaseDate
        developer
        publisher
        platform
        rating
        isActive
        headerImage
        screenshots
        videos {
            mp4
            webm
            thumbnail
        }
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
        updatedAt
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
    with open(output_file, "w", encoding="utf-8") as file:
        # Process products
        for product in data["data"]["products"]:
            product_id = product["_id"]
            title = product["title"].replace("'", "''")
            ptype = product["type"].replace("'", "''")
            description = product["description"].replace("'", "''")
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
            file.write(
                f"INSERT INTO products (id, title, type, description, price, discount, discountStartDate, discountEndDate, releaseDate, rating, isActive, headerImage)\n"
                f"VALUES ({product_id}, '{title}', '{ptype}', '{description}', {price}, {discount}, {discountStartDate}, {discountEndDate}, {releaseDate}, {rating}, {isActive}, '{headerImage}');\n\n"
            )

            # Insert developer in a single statement
            if product["developer"]:
                developers_values = ",\n".join(
                    [
                        f"({product_id}, '{developer.replace('\'', '\'\'')}')"
                        for developer in product["developer"]
                    ]
                )
                file.write(
                    f"INSERT INTO product_developers (product_id, developer) VALUES\n{developers_values};\n\n"
                )

            # Insert publisher in a single statement
            if product["publisher"]:
                publishers_values = ",\n".join(
                    [
                        f"({product_id}, '{publisher.replace('\'', '\'\'')}')"
                        for publisher in product["publisher"]
                    ]
                )
                file.write(
                    f"INSERT INTO product_publishers (product_id, publisher) VALUES\n{publishers_values};\n\n"
                )

            # insert platform in a single statement
            if product["platform"]:
                platform_values = ",\n".join(
                    [
                        f"({product_id}, '{platform.replace('\'', '\'\'')}')"
                        for platform in product["platform"]
                    ]
                )
                file.write(
                    f"INSERT INTO product_platforms (product_id, platform) VALUES\n{platform_values};\n\n"
                )

            # Insert genres in a single statement
            if product["genres"]:
                genres_values = ",\n".join(
                    [
                        f"({product_id}, '{genre.replace('\'', '\'\'')}')"
                        for genre in product["genres"]
                    ]
                )
                file.write(
                    f"INSERT INTO product_genres (product_id, genre) VALUES\n{genres_values};\n\n"
                )

            # Insert tags in a single statement
            if product["tags"]:
                tags_values = ",\n".join(
                    [
                        f"({product_id}, '{tag.replace('\'', '\'\'')}')"
                        for tag in product["tags"]
                    ]
                )
                file.write(
                    f"INSERT INTO product_tags (product_id, tag) VALUES\n{tags_values};\n\n"
                )

            # Insert features in a single statement
            if product["features"]:
                features_values = ",\n".join(
                    [
                        f"({product_id}, '{feature.replace('\'', '\'\'')}')"
                        for feature in product["features"]
                    ]
                )
                file.write(
                    f"INSERT INTO product_features (product_id, feature) VALUES\n{features_values};\n\n"
                )

            # Insert systemRequirements in a single statement
            if product["systemRequirements"]:
                for platform, requirements in product["systemRequirements"].items():
                    for requirement in requirements:
                        title = requirement["title"].replace("'", "''")
                        minimum = requirement["minimum"].replace("'", "''")
                        recommended = requirement["recommended"].replace("'", "''")
                        file.write(
                            f"INSERT INTO product_system_requirements (product_id, platform, title, minimum, recommended) "
                            f"VALUES ({product_id}, '{platform}', '{title}', '{minimum}', '{recommended}');\n\n"
                        )

            # Insert screenshots in a single statement
            if product["screenshots"]:
                screenshots_values = ",\n".join(
                    [
                        f"({product_id}, '{screenshot.replace('\'', '\'\'')}')"
                        for screenshot in product["screenshots"]
                    ]
                )
                file.write(
                    f"INSERT INTO product_screenshots (product_id, screenshot) VALUES\n{screenshots_values};\n\n"
                )

            # Insert videos in a single statement
            if product["videos"]:
                videos_values = ",\n".join(
                    [
                        f"({product_id}, '{video['mp4'].replace('\'', '\'\'')}', '{video['webm'].replace('\'', '\'\'')}', '{video['thumbnail'].replace('\'', '\'\'')}')"
                        for video in product["videos"]
                    ]
                )
                file.write(
                    f"INSERT INTO product_videos (product_id, mp4, webm, thumbnail) VALUES\n{videos_values};\n\n"
                )


# Ghi câu lệnh SQL vào file
def write_sql_to_file(sql_statements, filename):
    with open(filename, "w", encoding="utf-8") as file:
        file.write("\n".join(sql_statements))


# Chạy các bước
if __name__ == "__main__":
    try:
        # Lấy dữ liệu từ GraphQL API
        graphql_data = fetch_graphql_data(API_URL, QUERY)

        # Chuyển đổi JSON thành các câu lệnh SQL
        sql_statements = json_to_sql(graphql_data, "database/insert.sql")

        print("Đã ghi câu lệnh SQL vào tệp 'insert.sql'")

    except Exception as e:
        print(f"Có lỗi xảy ra: {e}")
