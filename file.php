<?php
class CompanyClass
{
    public function normalizeCompanyData(array $data): ?array
    {
        $company = [];
        if (!$this->isCompanyDataValid($data)) {
            return null;
        }
        $company["name"] = strtolower(trim($data["name"]));
        /* Validate if the address is proper http/s address and normalize to just the host*/
        if(preg_match("/\bhttps?:\/\/[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}(:\d+)?(\/\S*)?\b/u", $data["website"]))
        {
            $company["website"] = parse_url($data["website"], PHP_URL_HOST);
        }
        else{
            return null;
        }
        $this->processAddress($company, $data);
        return $company;
    }

    private function isCompanyDataValid(array $data): bool
    {
        /* Later tasks imply address is not important. */
        return isset($data["name"]) && isset($data["website"]);
    }

    private function processAddress(array $company, array $data): void
    {
        /*
         * If we have an non-empty address, use that.
         * Otherwise null. 
         */
        if (isset($data["address"])) {
            $company["address"] = trim($data["address"]);
            if (strlen($company["address"]) == 0) {
                $company["address"] = null;
            }
        }
        else
        {
            $company["address"] = null;
        }
    }
}
// Test Data
$input = [
    "name" => " OpenAI ",
    "website" => "https://openai.com/path?arg=val",
    "address" => " ",
];
$input2 = [
    "name" => "Innovatiespotter",
    "address" => "Groningen",
];
$input3 = [
    "name" => " Apple ",
    "website" => "xhttps://apple.com ",
];
$company = new CompanyClass();
$result = $company->normalizeCompanyData($input);
var_dump($result);
$result2 = $company->normalizeCompanyData($input2);
var_dump($result2);
$result3 = $company->normalizeCompanyData($input3);
var_dump($result3);
?>