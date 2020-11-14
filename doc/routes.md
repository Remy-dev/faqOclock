| <span class="bg-sucess">Routes</span> | <span class="bg-success"> Nom de la route</span> | <span class="bg-success">Méthodes Http </span>|
|:------: |:----------------:| :-------------: |
| / | question_list | GET |
| /tag/{name} | question_list_by_tag | GET |
| /question/{id} | question_show | GET, POST |
| /question/add | question_add | GET, POST |
| /admin/question/toggle/{id} | admin_question_toggle | PUT |
| /answer/validate/{id} | answer_validate | GET |
| /admin/answer/toggle/{id} | admin_answer_toggle | PUT |
| /login | login | GET, POST |
| /admin/tag | tag_index | GET |
| /admin/new | tag_new | GET, POST |
| /admin/{id} | tag_show | GET |
| /admin/{id}/edit | tag_edit | GET, POST|
| /admin/{id} | tag_delete | DELETE |
| /user/register | user_register | GET, POST |
| /user/profile | user_profile | GET |
| /user/edit | user_edit | GET, POST |
| /user/edit/password | user_edit_password | GET, POST |
| /admin/user | admin_user | GET |
| /admin/user/moderate/{id} | admin_user_moderate | GET, POST |


