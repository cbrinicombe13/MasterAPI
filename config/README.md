All tables stored in 'MasterAPI':

    Users table:

        Store the following: 

            id (auto_increment), username, email, pwd

        Actions:

            getUsers: no input
                      returns object or error message

            getSingleUser: username input in body
                           returns object or error message

            createUser: username, email, pwd input in body
                        returns array(
                            'created' => boolean
                            'authenticationSent' => boolean
                        ) or error message

            deleteUser: username input in body
                        returns array(
                            'deleted' => boolean
                        ) or error message

            TODO: 

                passwordReset
                updateDetails
                reauthenticate

    -------------------------------------------------------------------------------------

    Books table:

        Store the following:

            id (auto_increment), allowAccess

        Actions:

            createBook: $username
                Register new book in 'Books' table
                Create new table with name 'label'.

    -------------------------------------------------------------------------------------


    Contacts table (1 per user):

        Store the following:

            contactID, bookID, first name, last name, age, occupation, phone, email, address

            Sorted by bookID then by contactID.