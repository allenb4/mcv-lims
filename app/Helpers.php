<?php
use App\Models\Setting;
use App\Models\Patient;
use App\Models\Group;
use App\Models\Doctor;
use App\Mail\PatientCodeMail;
use App\Mail\TestsNotification;
use App\Mail\ReceiptMail;
use App\Mail\ReportMail;
use App\Mail\ResetPasswordMail;

if (!function_exists("patient_code")) {
    function patient_code($patient_id)
    {
        do {
            // Generate a random code using patient ID
            $code = mt_rand(1000, 9999) . $patient_id;

            // Check if the code already exists
            $exist = Patient::where("code", $code)->exists();
        } while ($exist); // Repeat until the code is unique

        // Update the patient record with the generated code
        Patient::where("id", $patient_id)->update(["code" => $code]);

        return $code; // Return the generated code
    }
}

if (!function_exists("print_bulk_barcode")) {
    function print_bulk_barcode($groups)
    {
        // Retrieve barcode settings
        $barcode_settings = setting("barcode");
        $pdf_name = "barcode.pdf";

        // Load the view for the barcode PDF
        $pdf = PDF::loadView(
            "pdf.bulk_barcode",
            compact("groups", "barcode_settings"),
            [],
            [
                "format" => [$barcode_settings["width"], $barcode_settings["height"]],
            ]
        );

        // Save the PDF to a specific location
        $pdf->save("uploads/pdf/" . $pdf_name);

        // Return the URL of the saved PDF
        return url("uploads/pdf/" . $pdf_name);
    }
}


if (!function_exists("generate_pdf")) {
    function generate_pdf($data = '', $type = 1)
    {
        $reports_settings = setting("reports");
        $info_settings = setting("info");
        $barcode_settings = setting("barcode");

        $pdf_name = "";
        $pdf = null;

        switch ($type) {
            case 1:
                $group = $data['group'];
                $categories = $data['categories'];
                $pdf_name = "report_{$group['id']}.pdf";
                // Generate PDF not yet working
                $pdf = PDF::loadView("pdf.report", compact("group", "categories", "reports_settings", "info_settings", "type", "barcode_settings"));
                break;
            case 2:
                $group = $data;
                \Log::info(['receiptData' => $group, 'reports_settings' => $reports_settings, 'info_settings' => $info_settings, 'barcode_settings' => $barcode_settings]);
                $pdf_name = "receipt_{$group['id']}.pdf";
                // Generate PDF not yet working
                $pdf = PDF::loadView("pdf.receipt", compact("group", "reports_settings", "info_settings", "type", "barcode_settings"));
                break;
            case 3:
                $group = $data;
                $pdf_name = "accounting.pdf";
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("pdf.accounting", compact("data", "reports_settings", "info_settings", "type"));
                break;
            case 4:
                $pdf_name = "doctor_report.pdf";
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("pdf.doctor_report", compact("data", "reports_settings", "info_settings", "type"));
                break;
            case 5:
                $pdf_name = "supplier_report.pdf";
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("pdf.supplier_report", compact("data", "reports_settings", "info_settings", "type"));
                break;
            case 6:
                $pdf_name = "purchase_report.pdf";
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("pdf.purchase_report", compact("data", "reports_settings", "info_settings", "type"));
                break;
            case 7:
                $group = $data;
                $pdf_name = "working_paper.pdf";
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("pdf.working_paper", compact("data", "group", "reports_settings", "info_settings", "type"));
                break;
            default:
                abort(404);
        }

        if ($pdf) {
            $pdf->save("uploads/pdf/{$pdf_name}");
            return url("uploads/pdf/{$pdf_name}");
        }

        return null;
    }
}

if (!function_exists("print_barcode")) {
    function print_barcode($group, $number)
    {
        $barcode_settings = setting("barcode");
        $pdf_name = "barcode.pdf";
        $pdf = PDF::loadView(
            "pdf.barcode",
            compact("group", "number", "barcode_settings"),
            [],
            ["format" => [$barcode_settings["width"], $barcode_settings["height"]]]
        );
        $pdf->save("uploads/pdf/{$pdf_name}");
        return url("uploads/pdf/{$pdf_name}");
    }
}


if (!function_exists("doctor_code")) {
    function doctor_code()
    {
        do {
            $code = mt_rand(1000, 9999);
            $exist = Doctor::where("code", $code)->count();
        } while ($exist > 0);

        return $code;
    }
}

if (!function_exists("print_bulk_working_paper")) {
    function print_bulk_working_paper($groups)
    {
        $reports_settings = setting("reports");
        $info_settings = setting("info");
        $type = 7; // Denotes the type of PDF being generated
        $pdf_name = "working_paper.pdf";

        $pdf = PDF::loadView(
            "pdf.bulk_working_paper",
            compact("groups", "reports_settings", "info_settings", "type")
        );
        $pdf->save("uploads/pdf/{$pdf_name}");
        return url("uploads/pdf/{$pdf_name}");
    }
}

if (!function_exists("get_currency")) {
    function get_currency()
    {
        if (cache()->has("currency")) {
            return cache("currency");
        }

        $setting = setting("info");
        $currency = $setting["currency"] ?? null;

        if ($currency) {
            cache()->put("currency", $currency);
        }

        return $currency;
    }
}


if (!function_exists("generate_barcode")) {
    function generate_barcode($group_id)
    {
        do {
            $barcode = mt_rand(1000, 9999) . $group_id;
            $exist = Group::where("barcode", $barcode)->exists();
        } while ($exist);

        Group::where("id", $group_id)->update(["barcode" => $barcode]);
    }
}


if (!function_exists("setting")) {
    function setting($key)
    {
        // Fetch and decode setting
        $setting = Setting::where("key", $key)->first();
        return $setting ? json_decode($setting["value"], true) : null;
    }
}

if (!function_exists("send_notification")) {
    function send_notification($type, $patient = null, $group = null, $user = null)
    {
        $email_settings = setting("emails");
        $sms_settings = setting("sms");

        // Handle email notifications
        if (!empty($patient["email"])) {
            if (isset($email_settings[$type]) && $email_settings[$type]["active"] === true) {
                try {
                    switch ($type) {
                        case "patient_code":
                            \Mail::to($patient["email"])->send(new PatientCodeMail($patient));
                            break;
                        case "receipt":
                            \Mail::to($patient["email"])->send(new ReceiptMail($patient, $group));
                            break;
                        case "report":
                            \Mail::to($patient["email"])->send(new ReportMail($patient, $group));
                            break;
                    }
                } catch (\Exception $e) {
                    // Handle exception (log or ignore)
                }
            }
        }

        // Handle user-specific email notifications
        if (!empty($user["email"])) {
            if (isset($email_settings[$type]) && $email_settings[$type]["active"] === true) {
                try {
                    if ($type === "reset_password") {
                        \Mail::to($user["email"])->send(new ResetPasswordMail($user));
                    }
                } catch (\Exception $e) {
                    // Handle exception (log or ignore)
                }
            }
        }

        // Handle SMS notifications
        if (isset($sms_settings[$type]) && $sms_settings[$type]["active"] === true) {
            if (!empty($patient["phone"])) {
                $message = str_replace(
                    ["{patient_code}", "{patient_name}"],
                    [$patient["code"] ?? '', $patient["name"] ?? ''],
                    $sms_settings[$type]["message"]
                );
                send_sms($patient["phone"], $message);
            }
        }
    }
}


//////////

if (!function_exists("formated_price")) {
    function formated_price($price)
    {
        // Check if currency is cached
        if (cache()->has("currency")) {
            return $price . " " . cache()->get("currency");
        }

        // If not cached, fetch and cache the currency
        $setting = Setting::where("key", "info")->first()["value"];
        $setting = json_decode($setting, true);
        $currency = $setting["currency"];

        cache()->put("currency", $currency);

        return $price . " " . $currency;
    }
}

if (!function_exists("group_test_calculations")) {
    function group_test_calculations($id)
    {
        // Fetch group data
        $group = Group::with("tests", "cultures", "contract")->where("id", $id)->first();
        $subtotal = 0;
        $paid = 0;
        $total = 0;
        $doctor_commission = 0;

        // Calculate the subtotal from various sources
        foreach (['tests', 'cultures', 'packages'] as $type) {
            if (isset($group[$type])) {
                foreach ($group[$type] as $item) {
                    $subtotal += $item["price"];
                }
            }
        }

        // Calculate payments
        if (count($group["payments"])) {
            foreach ($group["payments"] as $payment) {
                $paid += $payment["amount"];
            }
        }

        // Apply discount and calculate total
        $total = $subtotal - $group["discount"];

        // Calculate doctor commission if present
        if (isset($group["doctor"])) {
            $doctor_commission = $total * $group["doctor"]["commission"] / 100;
        }

        // Update group with calculated values
        $group->update([
            "subtotal" => $subtotal,
            "discount" => $group["discount"],
            "total" => $total,
            "paid" => $paid,
            "due" => $total - $paid,
            "doctor_commission" => $doctor_commission
        ]);
    }
}

if (!function_exists("get_system_date")) {
    function get_system_date($date = '', $format = '')
    {
        if (empty($date)) {
            return empty($format) ? date("Y-m-d") : date($format);
        }
        return empty($format) ? date("Y-m-d", strtotime($date)) : date($format, strtotime($date));
    }
}

if (!function_exists("send_sms")) {
    function send_sms($to, $message)
    {
        $sms_setting = setting("sms");

        // Check if Twilio is configured
        if ($sms_setting["gateway"] == "twilio" && !empty($sms_setting["twilio"]["sid"]) && !empty($sms_setting["twilio"]["token"]) && !empty($sms_setting["twilio"]["from"])) {
            $client = new \Twilio\Rest\Client($sms_setting["twilio"]["sid"], $sms_setting["twilio"]["token"]);
            try {
                $client->messages->create($to, ["from" => $sms_setting["twilio"]["from"], "body" => $message]);
            } catch (\Exception $e) {
                // Handle exception
            }
            return;
        }

        // Check if Nexmo is configured
        if ($sms_setting["gateway"] == "nexmo" && !empty($sms_setting["nexmo"]["key"]) && !empty($sms_setting["nexmo"]["secret"])) {
            $basic = new \Vonage\Client\Credentials\Basic($sms_setting["nexmo"]["key"], $sms_setting["nexmo"]["secret"]);
            $client = new \Vonage\Client($basic);
            try {
                $client->sms()->send(new \Vonage\SMS\Message\SMS($to, BRAND_NAME, $message));
            } catch (\Exception $e) {
                // Handle exception
            }
            return;
        }

        // Check if Infobip is configured
        if ($sms_setting["gateway"] == "infobip" && !empty($sms_setting["infobip"]["base_url"]) && !empty($sms_setting["infobip"]["key"])) {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $sms_setting["infobip"]["base_url"] . "/sms/2/text/advanced",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode([
                    "messages" => [
                        [
                            "from" => $sms_setting["infobip"]["from"],
                            "destinations" => [["to" => $to]],
                            "text" => $message
                        ]
                    ]
                ]),
                CURLOPT_HTTPHEADER => [
                    "Authorization: App " . $sms_setting["infobip"]["key"],
                    "Content-Type: application/json",
                    "Accept: application/json"
                ]
            ]);
            $response = curl_exec($curl);
            curl_close($curl);
            return;
        }

        // Check if LocalText is configured
        if ($sms_setting["gateway"] == "localText" && !empty($sms_setting["localText"]["key"]) && !empty($sms_setting["localText"]["sender"])) {
            $data = [
                "apikey" => urlencode($sms_setting["localText"]["key"]),
                "numbers" => [$to],
                "sender" => urlencode($sms_setting["localText"]["sender"]),
                "message" => rawurlencode($message)
            ];
            $ch = curl_init("https://api.textlocal.in/send/");
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $data
            ]);
            $response = curl_exec($ch);
            curl_close($ch);
            return;
        }
    }
}

if (!function_exists("check_group_done")) {
    function check_group_done($group_id)
    {
        // Fetch group data
        $group = Group::with(["tests", "cultures"])->where("id", $group_id)->first();
        $done = true;

        // Check if any culture is not done
        foreach ($group["all_cultures"] as $culture) {
            if (!$culture["done"]) {
                $done = false;
                break;
            }
        }

        // Check if any test is not done
        foreach ($group["all_tests"] as $test) {
            if (!$test["done"]) {
                $done = false;
                break;
            }
        }

        // Update group status and return result
        $group->update(["done" => $done]);
        return $done;
    }
}


////
if (!function_exists("whatsapp_notification")) {
    function whatsapp_notification($group, $type)
    {
        $whatsapp = setting("whatsapp");

        // Define the message and URL based on the type
        if ($type == "report") {
            $message = str_replace(
                ["{patient_name}", "{report_link}"],
                [$group["patient"]["name"], $group["report_pdf"]],
                $whatsapp["report"]["message"]
            );
        } elseif ($type == "receipt") {
            $message = str_replace(
                ["{patient_name}", "{receipt_link}"],
                [$group["patient"]["name"], $group["receipt_pdf"]],
                $whatsapp["receipt"]["message"]
            );
        } else {
            return null; // Invalid type or no action needed
        }

        // Generate the WhatsApp URL with the message
        $url = "https://wa.me/" . $group["patient"]["phone"] . "?text=" . urlencode($message);

        return $url;
    }
}

