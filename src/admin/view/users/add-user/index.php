<?php
$title = "Add user"; // Tiêu đề của trang
$activeSidebarLink = ['Pages', 'Users', 'Add User'];
ob_start(); // Bắt đầu lưu nội dung động
?>
<link rel="stylesheet" href="add-user.css">

<?php

// Xử lý form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $avatar = $_FILES['avatar'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $country = $_POST['country'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hiển thị dữ liệu
    echo 'avatar: ' . $avatar['name'] . '<br>';
    echo 'firstName: ' . $firstName . '<br>';
    echo 'lastName: ' . $lastName . '<br>';
    echo 'email: ' . $email . '<br>';
    echo 'phone: ' . $phone . '<br>';
    echo 'country: ' . $country . '<br>';
    echo 'username: ' . $username . '<br>';
    echo 'password: ' . $password . '<br>';
    echo 'role: ' . $role . '<br>';

    // Lưu dữ liệu vào database
    // ...

    // Chuyển hướng về trang danh sách
    // header('Location: /admin/pages/users/list.php');
}
?>

<article class="content">
    <!-- Page header -->
    <div class="page-header">
        <?php
        $breadcrumb = ['Pages', 'Users', 'Add User'];
        $pageHeader = 'Add User';
        include '../../../components/page-header.php';
        ?>
    </div>

    <!-- Form -->
    <form class="form" method="POST" action="" id="addUserForm" enctype="multipart/form-data">
        <div class="form-container">
            <!-- Step -->
            <ul class="step-list">
                <li class="step-item active focus">
                    <a class="step-content-wrapper" href="javascript:;">
                        <span class="step-icon">1</span>
                        <div class="step-content">
                            <span class="step-title">Profile</span>
                        </div>
                    </a>
                </li>

                <li class="step-item">
                    <a class="step-content-wrapper" href="javascript:;">
                        <span class="step-icon">2</span>
                        <div class="step-content">
                            <span class="step-title">Security Information</span>
                        </div>
                    </a>
                </li>

                <li class="step-item">
                    <a class="step-content-wrapper" href="javascript:;">
                        <span class="step-icon">3</span>
                        <div class="step-content">
                            <span class="step-title">Confirmation</span>
                        </div>
                    </a>
                </li>
            </ul>
            <!-- End Step -->

            <!-- Content Step Form -->
            <div id="addUserStepFormContent">
                <!-- Profile -->
                <div id="addUserStepProfile" class="card active">
                    <!-- Body -->
                    <table class="card-body">
                        <!-- AVATAR -->
                        <tr class="row form-group">
                            <td class="col-form-label">
                                <label>
                                    Avatar
                                </label>
                            </td>

                            <td class="col-form-input col-form-avatar">
                                <!-- Avatar -->
                                <label class="avatar-container" for="avatarUploader">
                                    <img id="avatarImg" class="avatar-img" src="/admin/assets/img/avatar/img1.jpg" alt="Image Description">

                                    <input class="avatar-uploader-input"
                                        id="avatarUploader"
                                        name="avatar"
                                        type="file"
                                        accept="image/*">

                                    <span class="avatar-uploader-trigger">
                                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="16px" width="16px" xmlns="http://www.w3.org/2000/svg">
                                            <path fill="none" d="M0 0h24v24H0z"></path>
                                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a.996.996 0 0 0 0-1.41l-2.34-2.34a.996.996 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"></path>
                                        </svg>
                                    </span>
                                </label>
                                <!-- End Avatar -->

                                <button id="deleteAvatarBtn" type="button" class="avatar-delete-btn">Delete</button>
                            </td>
                        </tr>
                        <!-- End AVATAR -->

                        <!-- FULL NAME -->
                        <tr class="row form-group">
                            <td class="col-form-label">
                                <label for="firstNameInput">
                                    Full name
                                </label>
                            </td>

                            <td class="col-form-input col-form-name">
                                <input type="text" class="form-control form-control-text" name="firstName" id="firstNameInput" placeholder="First name" aria-label="Clarice">
                                <input type="text" class="form-control form-control-text" name="lastName" id="lastNameInput" placeholder="Last name" aria-label="Boone">
                            </td>
                        </tr>
                        <!-- End FULL NAME -->

                        <!-- EMAIL -->
                        <tr class="row form-group">
                            <td class="col-form-label">
                                <label for="emailInput" class="col-sm-3 col-form-label input-label">
                                    Email
                                </label>
                            </td>

                            <td class="col-form-input">
                                <input type="email" class="form-control" name="email" id="emailInput" placeholder="clarice@example.com" aria-label="clarice@example.com">
                            </td>
                        </tr>
                        <!-- End EMAIL -->

                        <!-- PHONE -->
                        <tr class="row form-group">
                            <td class="col-form-label">
                                <label for="phoneInput" class="col-sm-3 col-form-label input-label">
                                    Phone
                                    <span class="input-label-secondary">
                                        (Optional)
                                    </span>
                                </label>
                            </td>

                            <td class="col-form-input">
                                <input type="text" class="js-masked-input form-control" name="phone" id="phoneInput" placeholder="+x(xxx)xxx-xx-xx" aria-label="+x(xxx)xxx-xx-xx" maxlength="13">
                            </td>
                        </tr>
                        <!-- End PHONE -->

                        <!-- COUNTRY -->
                        <tr class="row form-group">
                            <td class="col-form-label">
                                <label for="countrySelect" class="col-sm-3 col-form-label input-label">
                                    Country
                                </label>
                            </td>

                            <td class="col-form-input relative">
                                <select class="select-container" id="countrySelect" tabindex="-1" name="country">
                                    <option value="Afghanistan">Afghanistan</option>
                                    <option value="Åland Islands">Åland Islands</option>
                                    <option value="Albania">Albania</option>
                                    <option value="Algeria">Algeria</option>
                                    <option value="American Samoa">American Samoa</option>
                                    <option value="Andorra">Andorra</option>
                                    <option value="Angola">Angola</option>
                                    <option value="Anguilla">Anguilla</option>
                                    <option value="Antarctica">Antarctica</option>
                                    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Aruba">Aruba</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Austria">Austria</option>
                                    <option value="Azerbaijan">Azerbaijan</option>
                                    <option value="Bahamas">Bahamas</option>
                                    <option value="Bahrain">Bahrain</option>
                                    <option value="Bangladesh">Bangladesh</option>
                                    <option value="Barbados">Barbados</option>
                                    <option value="Belarus">Belarus</option>
                                    <option value="Belgium">Belgium</option>
                                    <option value="Belize">Belize</option>
                                    <option value="Benin">Benin</option>
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Bhutan">Bhutan</option>
                                    <option value="Bolivia">Bolivia</option>
                                    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                    <option value="Botswana">Botswana</option>
                                    <option value="Bouvet Island">Bouvet Island</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                    <option value="Brunei Darussalam">Brunei Darussalam</option>
                                    <option value="Bulgaria">Bulgaria</option>
                                    <option value="Burkina Faso">Burkina Faso</option>
                                    <option value="Burundi">Burundi</option>
                                    <option value="Cambodia">Cambodia</option>
                                    <option value="Cameroon">Cameroon</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Cape Verde">Cape Verde</option>
                                    <option value="Cayman Islands">Cayman Islands</option>
                                    <option value="Central African Republic">Central African Republic</option>
                                    <option value="Chad">Chad</option>
                                    <option value="Chile">Chile</option>
                                    <option value="China">China</option>
                                    <option value="Christmas Island">Christmas Island</option>
                                    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                    <option value="Colombia">Colombia</option>
                                    <option value="Comoros">Comoros</option>
                                    <option value="Congo">Congo</option>
                                    <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                                    <option value="Cook Islands">Cook Islands</option>
                                    <option value="Costa Rica">Costa Rica</option>
                                    <option value="Cote D'ivoire">Cote D'ivoire</option>
                                    <option value="Croatia">Croatia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Cyprus">Cyprus</option>
                                    <option value="Czech Republic">Czech Republic</option>
                                    <option value="Denmark">Denmark</option>
                                    <option value="Djibouti">Djibouti</option>
                                    <option value="Dominica">Dominica</option>
                                    <option value="Dominican Republic">Dominican Republic</option>
                                    <option value="Ecuador">Ecuador</option>
                                    <option value="Egypt">Egypt</option>
                                    <option value="El Salvador">El Salvador</option>
                                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                                    <option value="Eritrea">Eritrea</option>
                                    <option value="Estonia">Estonia</option>
                                    <option value="Ethiopia">Ethiopia</option>
                                    <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                    <option value="Faroe Islands">Faroe Islands</option>
                                    <option value="Fiji">Fiji</option>
                                    <option value="Finland">Finland</option>
                                    <option value="France">France</option>
                                    <option value="French Guiana">French Guiana</option>
                                    <option value="French Polynesia">French Polynesia</option>
                                    <option value="French Southern Territories">French Southern Territories</option>
                                    <option value="Gabon">Gabon</option>
                                    <option value="Gambia">Gambia</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Germany">Germany</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="Gibraltar">Gibraltar</option>
                                    <option value="Greece">Greece</option>
                                    <option value="Greenland">Greenland</option>
                                    <option value="Grenada">Grenada</option>
                                    <option value="Guadeloupe">Guadeloupe</option>
                                    <option value="Guam">Guam</option>
                                    <option value="Guatemala">Guatemala</option>
                                    <option value="Guernsey">Guernsey</option>
                                    <option value="Guinea">Guinea</option>
                                    <option value="Guinea-bissau">Guinea-bissau</option>
                                    <option value="Guyana">Guyana</option>
                                    <option value="Haiti">Haiti</option>
                                    <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                                    <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                    <option value="Honduras">Honduras</option>
                                    <option value="Hong Kong">Hong Kong</option>
                                    <option value="Hungary">Hungary</option>
                                    <option value="Iceland">Iceland</option>
                                    <option value="India">India</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                                    <option value="Iraq">Iraq</option>
                                    <option value="Ireland">Ireland</option>
                                    <option value="Isle of Man">Isle of Man</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italy">Italy</option>
                                    <option value="Jamaica">Jamaica</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Jersey">Jersey</option>
                                    <option value="Jordan">Jordan</option>
                                    <option value="Kazakhstan">Kazakhstan</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="Kiribati">Kiribati</option>
                                    <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                                    <option value="Korea, Republic of">Korea, Republic of</option>
                                    <option value="Kuwait">Kuwait</option>
                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                    <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                                    <option value="Latvia">Latvia</option>
                                    <option value="Lebanon">Lebanon</option>
                                    <option value="Lesotho">Lesotho</option>
                                    <option value="Liberia">Liberia</option>
                                    <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                                    <option value="Liechtenstein">Liechtenstein</option>
                                    <option value="Lithuania">Lithuania</option>
                                    <option value="Luxembourg">Luxembourg</option>
                                    <option value="Macao">Macao</option>
                                    <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                                    <option value="Madagascar">Madagascar</option>
                                    <option value="Malawi">Malawi</option>
                                    <option value="Malaysia">Malaysia</option>
                                    <option value="Maldives">Maldives</option>
                                    <option value="Mali">Mali</option>
                                    <option value="Malta">Malta</option>
                                    <option value="Marshall Islands">Marshall Islands</option>
                                    <option value="Martinique">Martinique</option>
                                    <option value="Mauritania">Mauritania</option>
                                    <option value="Mauritius">Mauritius</option>
                                    <option value="Mayotte">Mayotte</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                    <option value="Moldova, Republic of">Moldova, Republic of</option>
                                    <option value="Monaco">Monaco</option>
                                    <option value="Mongolia">Mongolia</option>
                                    <option value="Montenegro">Montenegro</option>
                                    <option value="Montserrat">Montserrat</option>
                                    <option value="Morocco">Morocco</option>
                                    <option value="Mozambique">Mozambique</option>
                                    <option value="Myanmar">Myanmar</option>
                                    <option value="Namibia">Namibia</option>
                                    <option value="Nauru">Nauru</option>
                                    <option value="Nepal">Nepal</option>
                                    <option value="Netherlands">Netherlands</option>
                                    <option value="Netherlands Antilles">Netherlands Antilles</option>
                                    <option value="New Caledonia">New Caledonia</option>
                                    <option value="New Zealand">New Zealand</option>
                                    <option value="Nicaragua">Nicaragua</option>
                                    <option value="Niger">Niger</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Niue">Niue</option>
                                    <option value="Norfolk Island">Norfolk Island</option>
                                    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                    <option value="Norway">Norway</option>
                                    <option value="Oman">Oman</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Palau">Palau</option>
                                    <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                                    <option value="Panama">Panama</option>
                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                    <option value="Paraguay">Paraguay</option>
                                    <option value="Peru">Peru</option>
                                    <option value="Philippines">Philippines</option>
                                    <option value="Pitcairn">Pitcairn</option>
                                    <option value="Poland">Poland</option>
                                    <option value="Portugal">Portugal</option>
                                    <option value="Puerto Rico">Puerto Rico</option>
                                    <option value="Qatar">Qatar</option>
                                    <option value="Reunion">Reunion</option>
                                    <option value="Romania">Romania</option>
                                    <option value="Russian Federation">Russian Federation</option>
                                    <option value="Rwanda">Rwanda</option>
                                    <option value="Saint Helena">Saint Helena</option>
                                    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                    <option value="Saint Lucia">Saint Lucia</option>
                                    <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                    <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                                    <option value="Samoa">Samoa</option>
                                    <option value="San Marino">San Marino</option>
                                    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                    <option value="Senegal">Senegal</option>
                                    <option value="Serbia">Serbia</option>
                                    <option value="Seychelles">Seychelles</option>
                                    <option value="Sierra Leone">Sierra Leone</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="Slovakia">Slovakia</option>
                                    <option value="Slovenia">Slovenia</option>
                                    <option value="Solomon Islands">Solomon Islands</option>
                                    <option value="Somalia">Somalia</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                                    <option value="Spain">Spain</option>
                                    <option value="Sri Lanka">Sri Lanka</option>
                                    <option value="Sudan">Sudan</option>
                                    <option value="Suriname">Suriname</option>
                                    <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                    <option value="Swaziland">Swaziland</option>
                                    <option value="Sweden">Sweden</option>
                                    <option value="Switzerland">Switzerland</option>
                                    <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                    <option value="Taiwan">Taiwan</option>
                                    <option value="Tajikistan">Tajikistan</option>
                                    <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Timor-leste">Timor-leste</option>
                                    <option value="Togo">Togo</option>
                                    <option value="Tokelau">Tokelau</option>
                                    <option value="Tonga">Tonga</option>
                                    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                    <option value="Tunisia">Tunisia</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Turkmenistan">Turkmenistan</option>
                                    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                    <option value="Tuvalu">Tuvalu</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="United Arab Emirates">United Arab Emirates</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="United States">United States</option>
                                    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                    <option value="Uruguay">Uruguay</option>
                                    <option value="Uzbekistan">Uzbekistan</option>
                                    <option value="Vanuatu">Vanuatu</option>
                                    <option value="Venezuela">Venezuela</option>
                                    <option value="Viet Nam">Viet Nam</option>
                                    <option value="Virgin Islands, British">Virgin Islands, British</option>
                                    <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                                    <option value="Wallis and Futuna">Wallis and Futuna</option>
                                    <option value="Western Sahara">Western Sahara</option>
                                    <option value="Yemen">Yemen</option>
                                    <option value="Zambia">Zambia</option>
                                    <option value="Zimbabwe">Zimbabwe</option>
                                </select>

                                <p class="selected-container" dir="ltr">
                                    <span class="selected-text" id="countryText">
                                        United Kingdom
                                        <span class="selected-arrow" role="presentation">
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="12px" width="12px" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M207.029 381.476L12.686 187.132c-9.373-9.373-9.373-24.569 0-33.941l22.667-22.667c9.357-9.357 24.522-9.375 33.901-.04L224 284.505l154.745-154.021c9.379-9.335 24.544-9.317 33.901.04l22.667 22.667c9.373 9.373 9.373 24.569 0 33.941L240.971 381.476c-9.373 9.372-24.569 9.372-33.942 0z"></path>
                                            </svg>
                                        </span>
                                    </span>
                                </p>
                            </td>
                        </tr>
                        <!-- End COUNTRY -->

                    </table>
                    <!-- End Body -->
                </div>
                <!-- End Profile -->

                <!-- Security Information -->
                <div id="addUserStepSecurityInformation" class="card" style="display: none;">
                    <!-- Body -->
                    <table class="card-body">
                        <!-- USERNAME -->
                        <tr class="row form-group">
                            <td class="col-form-label">
                                <label for="usernameInput" class="col-sm-3 col-form-label input-label">
                                    Username
                                </label>
                            </td>

                            <td class="col-form-input">
                                <input type="text" class="form-control" name="username" id="usernameInput" placeholder="Username" aria-label="Username">
                            </td>
                        </tr>
                        <!-- End USERNAME -->

                        <!-- PASSWORD -->
                        <tr class="row form-group">
                            <td class="col-form-label">
                                <label for="passwordInput" class="col-sm-3 col-form-label input-label">
                                    Password
                                </label>
                            </td>

                            <td class="col-form-input form-input-password">
                                <input type="password" class="form-control" name="password" id="passwordInput" placeholder="Password" aria-label="Password">
                                <!-- Button show password -->
                                <button class="show-password" type="button">
                                    <svg class="show-password-icon" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="16px" width="16px" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 11c-2.206 0-4-1.794-4-4s1.794-4 4-4 4 1.794 4 4-1.794 4-4 4z"></path>
                                        <path d="M12 10c-1.084 0-2 .916-2 2s.916 2 2 2 2-.916 2-2-.916-2-2-2z"></path>
                                    </svg>
                                    <svg class="hide-password-icon" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="16px" width="16px" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.073 12.194 4.212 8.333c-1.52 1.657-2.096 3.317-2.106 3.351L2 12l.105.316C2.127 12.383 4.421 19 12.054 19c.929 0 1.775-.102 2.552-.273l-2.746-2.746a3.987 3.987 0 0 1-3.787-3.787zM12.054 5c-1.855 0-3.375.404-4.642.998L3.707 2.293 2.293 3.707l18 18 1.414-1.414-3.298-3.298c2.638-1.953 3.579-4.637 3.593-4.679l.105-.316-.105-.316C21.98 11.617 19.687 5 12.054 5zm1.906 7.546c.187-.677.028-1.439-.492-1.96s-1.283-.679-1.96-.492L10 8.586A3.955 3.955 0 0 1 12.054 8c2.206 0 4 1.794 4 4a3.94 3.94 0 0 1-.587 2.053l-1.507-1.507z"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <!-- End PASSWORD -->

                        <!-- CONFIRM PASSWORD -->
                        <tr class="row form-group">
                            <td class="col-form-label">
                                <label for="confirmPasswordInput" class="col-sm-3 col-form-label input-label">
                                    Confirm password
                                </label>
                            </td>

                            <td class="col-form-input form-input-password">
                                <input type="password" class="form-control" name="confirmPassword" id="confirmPasswordInput" placeholder="Confirm password" aria-label="Confirm password">
                                <!-- Button show password -->
                                <button class="show-password" type="button">
                                    <svg class="show-password-icon" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="16px" width="16px" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 11c-2.206 0-4-1.794-4-4s1.794-4 4-4 4 1.794 4 4-1.794 4-4 4z"></path>
                                        <path d="M12 10c-1.084 0-2 .916-2 2s.916 2 2 2 2-.916 2-2-.916-2-2-2z"></path>
                                    </svg>
                                    <svg class="hide-password-icon" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="16px" width="16px" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.073 12.194 4.212 8.333c-1.52 1.657-2.096 3.317-2.106 3.351L2 12l.105.316C2.127 12.383 4.421 19 12.054 19c.929 0 1.775-.102 2.552-.273l-2.746-2.746a3.987 3.987 0 0 1-3.787-3.787zM12.054 5c-1.855 0-3.375.404-4.642.998L3.707 2.293 2.293 3.707l18 18 1.414-1.414-3.298-3.298c2.638-1.953 3.579-4.637 3.593-4.679l.105-.316-.105-.316C21.98 11.617 19.687 5 12.054 5zm1.906 7.546c.187-.677.028-1.439-.492-1.96s-1.283-.679-1.96-.492L10 8.586A3.955 3.955 0 0 1 12.054 8c2.206 0 4 1.794 4 4a3.94 3.94 0 0 1-.587 2.053l-1.507-1.507z"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <!-- End CONFIRM PASSWORD -->

                        <!-- ROLE -->
                        <tr class="row form-group">
                            <td class="col-form-label">
                                <labe class="col-sm-3 col-form-label input-label">
                                    Role
                                    </label>
                            </td>

                            <td class="col-form-input col-form-radio">
                                <div class="form-control-radio">
                                    <input type="radio" class="form-control" name="role" value="Admin" id="adminInput">
                                    <label for="adminInput">Admin</label>
                                </div>
                                <div class="form-control-radio">
                                    <input type="radio" class="form-control" name="role" value="User" id="userInput" checked>
                                    <label for="userInput">User</label>
                                </div>
                            </td>
                        </tr>
                        <!-- End ROLE -->
                    </table>
                </div>
                <!-- End BillingAddress -->

                <!-- Confirmation -->
                <div id="addUserStepConfirmation" class="card card-confirm" style="display: none;">
                    <!-- Profile Cover -->
                    <div class="profile-cover">
                        <div class="profile-cover-img-wrapper">
                            <img class="profile-cover-img" src="/admin/assets/img/add-user/profile-cover.jpg" alt="Image Description">
                        </div>
                    </div>
                    <!-- End Profile Cover -->

                    <!-- Avatar -->
                    <div class="profile-cover-avatar">
                        <img id="confirmAvatar" class="avatar-img" src="/admin/assets/img/avatar/img1.jpg" alt="Image Description">
                    </div>
                    <!-- End Avatar -->

                    <!-- Body -->
                    <div class="card-body">
                        <dl class="confirm-row">
                            <dt class="confirm-term">Full name:</dt>
                            <dd id="confirmFullName" class="confirm-value">-</dd>

                            <dt class="confirm-term">Email:</dt>
                            <dd id="confirmEmail" class="confirm-value">-</dd>

                            <dt class="confirm-term">Phone:</dt>
                            <dd id="confirmPhone" class="confirm-value">-</dd>

                            <dt class="confirm-term">Country:</dt>
                            <dd id="confirmCountry" class="confirm-value">-</dd>

                            <dt class="confirm-term">Username:</dt>
                            <dd id="confirmUsername" class="confirm-value">-</dd>

                            <dt class="confirm-term">Password:</dt>
                            <dd id="confirmPassword" class="confirm-value">-</dd>

                            <dt class="confirm-term">Role:</dt>
                            <dd id="confirmRole" class="confirm-value">-</dd>
                        </dl>
                        <!-- End Row -->
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Confirmation -->

                <!-- Footer -->
                <div id="btn-form" class="card-footer">
                    <!-- Previous -->
                    <button id="btnPreviousForm" type="button" class="btn-previous" style="display: none;">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 320 512" height="14px" width="14px" xmlns="http://www.w3.org/2000/svg">
                            <path d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"></path>
                        </svg>
                        Previous
                    </button>
                    <!-- Next -->
                    <button id="btnNextForm" type="button" class="btn-next">
                        Next
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 320 512" height="14px" width="14px" xmlns="http://www.w3.org/2000/svg">
                            <path d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"></path>
                        </svg>
                    </button>
                </div>
                <!-- End Footer -->
            </div>
            <!-- End Content Step Form -->

            <!-- Message Body -->
            <div id="successMessageContent" style="display:none;">
                <div class="text-center">
                    <!-- <img class="img-fluid mb-3" src="assets\svg\illustrations\hi-five.svg" alt="Image Description" style="max-width: 15rem;"> -->

                    <div class="mb-4">
                        <h2>Successful!</h2>
                        <p>New <span class="font-weight-bold text-dark">Ella Lauda</span> user has been successfully created.</p>
                    </div>

                    <div class="d-flex justify-content-center">
                        <a class="btn btn-white mr-3" href="users.html">
                            <i class="tio-chevron-left ml-1"></i> Back to users
                        </a>
                        <a class="btn btn-primary" href="users-add-user.html">
                            <i class="tio-user-add mr-1"></i> Add new user
                        </a>
                    </div>
                </div>
            </div>
            <!-- End Message Body -->
        </div>
    </form>
    <!-- End Form -->
</article>

<script src="add-user.js"></script>

<?php
$content = ob_get_clean(); // Lấy nội dung và lưu vào biến $content
include '../../../layout.php'; // Nạp layout chính