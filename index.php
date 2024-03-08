<?php
function formatIndianCurrency($number) {
    $formatted_number = '';

    // Convert the number to string
    $number_str = strval($number);

    // Check if number is negative
    $is_negative = ($number_str[0] == '-');
    if ($is_negative) {
        $formatted_number .= '-';
        $number_str = substr($number_str, 1); // Remove the negative sign for now
    }

    // Split the number into integer and decimal parts
    $parts = explode('.', $number_str);

    // Format the integer part
    $integer_part = $parts[0];
    $integer_length = strlen($integer_part);
    $integer_formatted = '';

    // Handle Indian numbering system formatting for integer part
    for ($i = $integer_length - 1, $count = 0; $i >= 0; $i--, $count++) {
        $integer_formatted = $integer_part[$i] . $integer_formatted;
        if (($count + 1) % 2 == 0 && $i != 0) {
            $integer_formatted = ',' . $integer_formatted;
        }
        if (($count + 1) % 2 == 0 && $i == 0 && $integer_length % 2 == 0) {
            $integer_formatted = ',' . $integer_formatted;
        }
    }

    $formatted_number .= $integer_formatted;

    // Append the decimal part if it exists
    if (isset($parts[1])) {
        $formatted_number .= '.' . $parts[1];
    }

    return $formatted_number;
}

// Test the function
$number = 1000000; // 10,00,000
$formatted_number = formatIndianCurrency($number);
echo $formatted_number; // Output: 10,00,000
?>

