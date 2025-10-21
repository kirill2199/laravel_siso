#!/bin/bash

# Configuration
BASE_URL="http://localhost:8000"
TOKEN="secret123"  # Используем токен из конфигурации
TODAY=$(date +%Y-%m-%d)
OUTPUT_DIR="/var/www/html/tmp"
LOG_FILE="${OUTPUT_DIR}/api_test.log"

# Create output directory if not exists
mkdir -p "$OUTPUT_DIR"
chmod 755 "$OUTPUT_DIR"

# Function to test API endpoint and save results
test_endpoint() {
    local endpoint=$1
    local params=$2
    local description=$3
    local output_file="${OUTPUT_DIR}/${endpoint}_response.json"
    local summary_file="${OUTPUT_DIR}/${endpoint}_summary.json"
    
    echo "=== Testing $description ===" | tee -a "$LOG_FILE"
    echo "URL: ${BASE_URL}/api/${endpoint}?${params}&key=${TOKEN}" | tee -a "$LOG_FILE"
    
    # Make API request and save full response
    response=$(curl -s -w "\nHTTP_CODE:%{http_code}\nTIME:%{time_total}" "${BASE_URL}/api/${endpoint}?${params}&key=${TOKEN}")
    http_code=$(echo "$response" | grep "HTTP_CODE:" | cut -d':' -f2)
    response_time=$(echo "$response" | grep "TIME:" | cut -d':' -f2)
    body=$(echo "$response" | grep -v "HTTP_CODE:" | grep -v "TIME:")
    
    # Save full response
    echo "$body" > "$output_file"
    
    # Create summary
    if [ "$http_code" -eq 200 ]; then
        if command -v jq >/dev/null 2>&1 && echo "$body" | jq empty 2>/dev/null; then
            data_count=$(echo "$body" | jq -r '.data | length' 2>/dev/null || echo "0")
            total=$(echo "$body" | jq -r '.meta.total' 2>/dev/null || echo "0")
            current_page=$(echo "$body" | jq -r '.meta.current_page' 2>/dev/null || echo "0")
            per_page=$(echo "$body" | jq -r '.meta.per_page' 2>/dev/null || echo "0")
            
            summary=$(cat <<EOF
{
    "endpoint": "$endpoint",
    "description": "$description",
    "http_code": $http_code,
    "response_time": $response_time,
    "timestamp": "$(date -Iseconds)",
    "data_count": $data_count,
    "total_records": $total,
    "current_page": $current_page,
    "per_page": $per_page,
    "url": "${BASE_URL}/api/${endpoint}?${params}&key=${TOKEN}",
    "full_response_file": "$output_file",
    "status": "success"
}
EOF
)
        else
            summary=$(cat <<EOF
{
    "endpoint": "$endpoint",
    "description": "$description",
    "http_code": $http_code,
    "response_time": $response_time,
    "timestamp": "$(date -Iseconds)",
    "url": "${BASE_URL}/api/${endpoint}?${params}&key=${TOKEN}",
    "full_response_file": "$output_file",
    "status": "success_non_json"
}
EOF
)
        fi
        
        echo "✅ SUCCESS - HTTP $http_code" | tee -a "$LOG_FILE"
        echo "⏱️ Response time: ${response_time}s" | tee -a "$LOG_FILE"
        
    elif [ "$http_code" -eq 401 ]; then
        summary=$(cat <<EOF
{
    "endpoint": "$endpoint",
    "description": "$description",
    "http_code": $http_code,
    "response_time": $response_time,
    "timestamp": "$(date -Iseconds)",
    "url": "${BASE_URL}/api/${endpoint}?${params}&key=${TOKEN}",
    "full_response_file": "$output_file",
    "status": "unauthorized",
    "error": "Invalid API token"
}
EOF
)
        echo "🔐 UNAUTHORIZED - HTTP $http_code" | tee -a "$LOG_FILE"
        
    elif [ "$http_code" -eq 404 ]; then
        summary=$(cat <<EOF
{
    "endpoint": "$endpoint",
    "description": "$description",
    "http_code": $http_code,
    "response_time": $response_time,
    "timestamp": "$(date -Iseconds)",
    "url": "${BASE_URL}/api/${endpoint}?${params}&key=${TOKEN}",
    "full_response_file": "$output_file",
    "status": "not_found",
    "error": "Endpoint not found"
}
EOF
)
        echo "❌ NOT FOUND - HTTP $http_code" | tee -a "$LOG_FILE"
        
    else
        summary=$(cat <<EOF
{
    "endpoint": "$endpoint",
    "description": "$description",
    "http_code": $http_code,
    "response_time": $response_time,
    "timestamp": "$(date -Iseconds)",
    "url": "${BASE_URL}/api/${endpoint}?${params}&key=${TOKEN}",
    "full_response_file": "$output_file",
    "status": "error",
    "error": "HTTP $http_code"
}
EOF
)
        echo "❌ ERROR - HTTP $http_code" | tee -a "$LOG_FILE"
    fi
    
    echo "$summary" | jq '.' > "$summary_file" 2>/dev/null || echo "$summary" > "$summary_file"
    echo "💾 Full response: $output_file" | tee -a "$LOG_FILE"
    echo "📋 Summary: $summary_file" | tee -a "$LOG_FILE"
    echo "----------------------------------------" | tee -a "$LOG_FILE"
}

# Clear previous log
echo "API Test started at: $(date)" > "$LOG_FILE"
echo "Base URL: $BASE_URL" >> "$LOG_FILE"
echo "Token: $TOKEN" >> "$LOG_FILE"
echo "========================================" >> "$LOG_FILE"

# Test all endpoints
test_endpoint "sales" "dateFrom=2024-01-01&dateTo=2024-01-31" "Sales API"
test_endpoint "orders" "dateFrom=2024-01-01&dateTo=2024-01-31" "Orders API" 
test_endpoint "stocks" "dateFrom=${TODAY}" "Stocks API"
test_endpoint "incomes" "dateFrom=2024-01-01&dateTo=2024-01-31" "Incomes API"

# Generate overall report
report_file="${OUTPUT_DIR}/api_test_report.json"
overall_report=$(cat <<EOF
{
    "test_timestamp": "$(date -Iseconds)",
    "base_url": "$BASE_URL",
    "test_date": "$TODAY",
    "api_token_used": "$TOKEN",
    "endpoints_tested": [
        {
            "name": "sales",
            "description": "Sales API",
            "full_response": "${OUTPUT_DIR}/sales_response.json",
            "summary": "${OUTPUT_DIR}/sales_summary.json"
        },
        {
            "name": "orders", 
            "description": "Orders API",
            "full_response": "${OUTPUT_DIR}/orders_response.json",
            "summary": "${OUTPUT_DIR}/orders_summary.json"
        },
        {
            "name": "stocks",
            "description": "Stocks API", 
            "full_response": "${OUTPUT_DIR}/stocks_response.json",
            "summary": "${OUTPUT_DIR}/stocks_summary.json"
        },
        {
            "name": "incomes",
            "description": "Incomes API",
            "full_response": "${OUTPUT_DIR}/incomes_response.json", 
            "summary": "${OUTPUT_DIR}/incomes_summary.json"
        }
    ],
    "log_file": "$LOG_FILE",
    "output_directory": "$OUTPUT_DIR"
}
EOF
)

echo "$overall_report" | jq '.' > "$report_file" 2>/dev/null || echo "$overall_report" > "$report_file"

# Display final information
echo "========================================" | tee -a "$LOG_FILE"
echo "🎉 API Testing Completed!" | tee -a "$LOG_FILE"
echo "📁 All files saved in: $OUTPUT_DIR" | tee -a "$LOG_FILE"

# Display quick summary
echo -e "\n📊 Quick Summary:" | tee -a "$LOG_FILE"
for endpoint in sales orders stocks incomes; do
    summary_file="${OUTPUT_DIR}/${endpoint}_summary.json"
    if [ -f "$summary_file" ]; then
        if command -v jq >/dev/null 2>&1; then
            http_code=$(jq -r '.http_code' "$summary_file" 2>/dev/null || echo "?")
            status=$(jq -r '.status' "$summary_file" 2>/dev/null || echo "unknown")
        else
            http_code=$(grep -o '"http_code":[^,]*' "$summary_file" | cut -d':' -f2 | tr -d ' ' || echo "?")
            status=$(grep -o '"status":"[^"]*"' "$summary_file" | cut -d'"' -f4 || echo "unknown")
        fi
        echo "  $endpoint: HTTP $http_code ($status)" | tee -a "$LOG_FILE"
    else
        echo "  $endpoint: ❌ No summary file" | tee -a "$LOG_FILE"
    fi
done

echo -e "\n📄 Full log: $LOG_FILE" | tee -a "$LOG_FILE"
echo "📋 Overall report: $report_file" | tee -a "$LOG_FILE"