import requests
import json
from datetime import datetime

# Định nghĩa GraphQL API endpoint và truy vấn
API_URL = "https://mail.lehuy.id.vn/graphql"  # Thay thế bằng URL GraphQL API của bạn
QUERY = """
query {
    products {
        id
        title
        type
        description
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

header_request='content-type: application/json'

# Hàm xử lý yêu cầu hệ thống
def process_system_requirements(product_id, system_requirements):
    insert_statements = []
    for os_type, requirements in system_requirements.items():
        for req in requirements:
            title = req.get("title", "")
            minimum = req.get("minimum", "").replace("'", "''")  # Escape single quotes
            recommended = req.get("recommended", "").replace("'", "''")
            statement = f"""
            INSERT INTO product_system_requirements (product_id, os_type, title, minimum, recommended)
            VALUES ({product_id}, '{os_type}', '{title}', '{minimum}', '{recommended}');
            """
            insert_statements.append(statement)
    return insert_statements

# Hàm gửi yêu cầu GraphQL
def fetch_graphql_data(api_url, query):
    response = requests.post(api_url, json={'query': query}, headers=header_request)
    response.raise_for_status()
    return response.json()

# Hàm chuyển đổi dữ liệu JSON thành các câu lệnh SQL
def json_to_sql(data, output_file):
    
    print(data)
    with open(output_file, 'w', encoding='utf-8') as file:
        # Process products
        for product in data['data']['products']:
            product_id = product['_id']
            title = product['title'].replace("'", "''")
            ptype = product['type'].replace("'", "''")
            description = product['description'].replace("'", "''")
            price = product['price']
            discount = product['discount']
            discount_start_date = (f"'{datetime.fromtimestamp(int(product['discountStartDate']) / 1000).strftime('%Y-%m-%d')}'" if product['discountStartDate'] else 'NULL')
            discount_end_date = (f"'{datetime.fromtimestamp(int(product['discountEndDate']) / 1000).strftime('%Y-%m-%d')}'" if product['discountEndDate'] else 'NULL')
            release_date = f"'{datetime.fromtimestamp(int(product['releaseDate']) / 1000).strftime('%Y-%m-%d')}'"
            rating = product['rating']
            is_active = 'TRUE' if product['isActive'] else 'FALSE'
            header_image = product['headerImage'].replace("'", "''")

            # Insert product
            file.write(f"INSERT INTO products (id, title, type, description, price, discount, discount_start_date, discount_end_date, release_date, rating, is_active, header_image)\n"
                       f"VALUES ({product_id}, '{title}', '{ptype}', '{description}', {price}, {discount}, {discount_start_date}, {discount_end_date}, {release_date}, {rating}, {is_active}, '{header_image}');\n\n")

            # Insert screenshots
            for screenshot in product['screenshots']:
                screenshot = screenshot.replace("'", "''")
                file.write(f"INSERT INTO product_screenshots (product_id, screenshot_url) VALUES ({product_id}, '{screenshot}');\n")

            # Insert genres
            for genre in product['genres']:
                genre = genre.replace("'", "''")
                file.write(f"INSERT INTO product_genres (product_id, genre) VALUES ({product_id}, '{genre}');\n")

            # Insert tags
            for tag in product['tags']:
                tag = tag.replace("'", "''")
                file.write(f"INSERT INTO product_tags (product_id, tag) VALUES ({product_id}, '{tag}');\n")

            # Insert features
            for feature in product['features']:
                feature = feature.replace("'", "''")
                file.write(f"INSERT INTO product_features (product_id, feature) VALUES ({product_id}, '{feature}');\n")

            # Insert system requirements
            for os_type, requirements in product['systemRequirements'].items():
                for req in requirements:
                    title = req['title'].replace("'", "''")
                    minimum = req['minimum'].replace("'", "''")
                    recommended = req['recommended'].replace("'", "''")
                    file.write(f"INSERT INTO product_system_requirements (product_id, os_type, title, minimum, recommended)\n"
                               f"VALUES ({product_id}, '{os_type}', '{title}', '{minimum}', '{recommended}');\n")

# Ghi câu lệnh SQL vào file
def write_sql_to_file(sql_statements, filename):
    with open(filename, 'w', encoding='utf-8') as file:
        file.write("\n".join(sql_statements))

# Chạy các bước
if __name__ == "__main__":
    try:
        # Lấy dữ liệu từ GraphQL API
        graphql_data = fetch_graphql_data(API_URL, QUERY)
        
        
        # Ghi vào json
        with open('python/output.json', 'w', encoding='utf-8') as file:
            json.dump(graphql_data, file, ensure_ascii=False, indent=4)
            print("Đã ghi dữ liệu vào tệp 'output.json'")
        
        # Chuyển đổi JSON thành các câu lệnh SQL
        sql_statements = json_to_sql(graphql_data, "python/output.sql")
        
        # Ghi câu lệnh SQL vào tệp
        # write_sql_to_file(sql_statements, "output.sql")
        
        print("Đã ghi câu lệnh SQL vào tệp 'output.sql'")
    except Exception as e:
        print(f"Có lỗi xảy ra: {e}")
