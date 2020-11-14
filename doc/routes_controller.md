| <span class="bg-sucess">Routes</span> | <span class="bg-success"> Nom de la route</span> | <span class="bg-success"> ->methode() </span>|
|:------: |:----------------:| :-------------: |
| /api/list | App\Controller\Api\ApiQuestionController | questionList() |
| /api/{id}/view | App\Controller\Api\ApiQuestionController | questionView() |
| /api/add | App\Controller\Api\ApiQuestionController | addQuestion() |
| /api/response | App\Controller\Api\ApiAnswerController | addAnswerResponse() |
| /api/list | App\Controller\Api\ApiTagController| taglist() |
| /api/tag/{name} | App\Controller\Api\ApiTagController | getQuestionByTag() |