<div class="panel-container">
    <button class="panel-container__exit-bttn" onclick="closeUserEdit()">&times;</button>

    <h1 class="panel-container__header">User details</h1>

    <div class="modal-content__info modal-content__info--edit"></div>

    <form class = "editUser" action = "editUser" method="post">
        <h1 class="info-box__text info-box__text--key">Company: </h1>
        <div class="panel-container__info-box panel-container__info-box--static">
            <h1 class="info-box__text" id="userCompany"></h1>
        </div>

        <h1 class="info-box__text info-box__text--key">Role: </h1>
        <div class="panel-container__info-box panel-container__info-box--static">
            <h1 class="info-box__text" id="userRole"></h1>
        </div>

        <h1 class="info-box__text info-box__text--key">Name: </h1>
        <div class="panel-container__info-box">
            <h1 class="info-box__text" id="userName"></h1>
        </div>
        <input name="name" type="text" class="panel-container__info-box panel-container__info-box--input" id="userNameEdit">


        <h1 class="info-box__text info-box__text--key">Surname: </h1>
        <div class="panel-container__info-box">
            <h1 class="info-box__text" id="userSurname"></h1>
        </div>
        <input name="surname" type="text" class="panel-container__info-box panel-container__info-box--input" id="userSurnameEdit">


        <h1 class="info-box__text info-box__text--key">Email: </h1>
        <div class="panel-container__info-box">
            <h1 class="info-box__text" id="userEmail"></h1>
        </div>
        <input name="email" type="email" class="panel-container__info-box panel-container__info-box--input" id="userEmailEdit">

        <h1 class="info-box__text info-box__text--key">Password: </h1>
        <div class="panel-container__password">
            <div class="panel-container__info-box" id="passwordBox">
                <h1 class="info-box__text">************</h1>
            </div>
            <input name="pass" type="password" class="panel-container__info-box panel-container__info-box--input" id="userPasswordEdit">
            <input type="checkbox" class="panel-container__password-checkbox">
        </div>


        <div class="panel-container__btn-box">
            <button class="btn-box__button btn-box__button--edit" onclick="editUserInfo()">Edit</button>
            <button class="btn-box__button btn-box__button--logout" onclick="logout()">Logout</button>
            <button class="btn-box__button btn-box__button--submit">Submit</button>
        </div>
    </form>
</div>





<div class="layout__main-content layout__main-content--admin">
    <div class="main-content__toolbar">
        <select class="main-content__toolbar-select" id="searchMethod" name="searchMethod">
            <option value=user_surname>User surname</option>
            <option value=user_email>User email</option>
        </select>
        <input class="main-content__toolbar-search" type="text" placeholder="Search for user  by name..." id="usersSearchInput" oninput="searchUserByPrefix(this.value)">
        <button class="main-content__toolbar-button" type="button" onclick="openAddUserModal()">Add User</button>
        <button class="main-content__toolbar-button main-content__toolbar-button--logout" onclick="logout()">Logout</button>

    </div>
    <div class="main-content__table-container">
        <table class="main-content__table">
            <thead class="main-content__table-header">
            <tr>
                <th class="main-content__table-header-cell">Name</th>
                <th class="main-content__table-header-cell">Surname</th>
                <th class="main-content__table-header-cell">Email</th>
                <th class="main-content__table-header-cell">Role</th>
                <th class="main-content__table-header-cell">Actions</th>
            </tr>
            </thead>
            <tbody class="main-content__table-body">
            </tbody>
        </table>
    </div>
    <div class="main-content__pagination"></div>

    <div class="modal" id="addUserModal">
        <div class="modal-content modal-content">
            <button class="modal-content__close-button" type="button" onclick="closeAddUserModal()">&times;</button>

            <h1 class="modal-content__title">Add user</h1>

            <div class="modal-content__info modal-content__info--add-user"></div>

            <form class="add-user" action="registerUser" method="POST">
                <div class="modal-content__form-section">
                    <input class="modal-content__form-section-input" id="userCreateName" type="text" name="userName" placeholder="Name">
                    <input class="modal-content__form-section-input" id="userCreateSurname" type="text" name="userSurname" placeholder="Surname">
                    <input class="modal-content__form-section-input" id="userCreateEmail" type="email" name="userEmail" placeholder="Email">
                     <select class="modal-content__form-section-input modal-content__form-section-select" id="userCreateRole" name="userRoleID">
                        <option value="">Select a role</option>
                        <option value="2">Moderator</option>
                        <option value="3">Accountant</option>
                    </select>
                    <input class="modal-content__form-section-input" id="userCreatePassword" type="password" name="userPassword" placeholder="Password">
                    <input class="modal-content__form-section-input" id="userCreatePasswordConfirm" type="password" name="userConfirmPassword" placeholder="Confirm password">
                    <button class="modal-content__form-section-button" id="createUserBttn"  type="submit">Create User</button>
                </div>
            </form>
        </div>
    </div>


    <div class="modal" id="deleteUserModal">
        <div class="modal-content">
            <button class="modal-content__close-button" onclick="closeDeleteUserModal()">&times;</button>
            <h1 class="modal-content__title">User will not exist anymore</h1>
            <div class="modal-content__form-section">
                <button class="modal-content__form-section-button modal-content__form-section-delete-button" class="" type="button">Delete</button>
            </div>
        </div>
    </div>




</div>



