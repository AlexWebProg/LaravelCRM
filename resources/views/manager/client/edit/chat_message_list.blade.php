@if (!empty($messages) and count($messages))
    <div class="col-12">
        <div class="row">
            @foreach ($messages as $message)
                <div id="message{{ $message->id }}" class="card col-10 pl-0 pr-0 mt-3 {{ (!empty($message->manager_id)) ? 'my_message offset-2' : 'foreign_message' }} {{ (!empty($message->un_viewed)) ? 'un_viewed' : '' }}" data-text="{{ $message->text }}" data-author="{{ $message->author }}">
                    <div class="card-body">
                        @if (!empty($message->quiz_survey_id) && empty($message->deleted_at))
                            @if (!empty($message->quiz_survey_completed_at) || empty($message->survey?->template))
                                Спасибо за участие в опросе!
                                @foreach ($message->survey->template->questions as $question)
                                    <hr/>
                                    <p><b>{{ $question->text }}</b></p>
                                    @if (!empty($message->survey->client_question_answer($message->quiz_survey_id,$message->client_id,$question->id)->rating))
                                        <p>{!! $message->survey->client_question_answer($message->quiz_survey_id,$message->client_id,$question->id)->rating_star_with_empty($question->rating_to,true,true) !!}</p>
                                    @endif
                                    @if (!empty($message->survey->client_question_answer($message->quiz_survey_id,$message->client_id,$question->id)->comment))
                                        <p>{!! $message->survey->client_question_answer($message->quiz_survey_id,$message->client_id,$question->id)->comment !!}</p>
                                    @endif
                                @endforeach
                                <hr/>
                                <small class="text-muted">
                                    Заказчик ответил на опрос {{ date('d.m.Y H:i',strtotime($message->quiz_survey_completed_at)) }}
                                </small>
                            @else
                                {{ $message->survey->template->intro }}
                                @foreach ($message->survey->template->questions as $question)
                                    <hr/>
                                    <p><b>{{ $question->text }}</b></p>
                                    @if (!empty($question->rating_enabled))
                                        <div class="rating_block">
                                            <div class="rating" data-rating-from="{{ $question->rating_from }}" data-rating-to="{{ $question->rating_to }}" data-input-name="rating[{{ $question->id }}]"></div>
                                        </div>
                                    @endif
                                    @if (!empty($question->comment_enabled))
                                        <div class="form-group mt-2 comment_block">
                                            <textarea class="form-control" rows="3" name="comment[{{ $question->id }}]" placeholder="Комментарий"></textarea>
                                        </div>
                                    @endif
                                @endforeach
                                <hr/>
                                <small class="text-muted">
                                    Заказчик ещё не ответил на опрос
                                </small>
                            @endif
                        @endif
                        @if (!empty($message->reply_message_id) && !empty($message->replied_message) && empty($message->deleted_at))
                            <div class="callout {{ (!empty($message->manager_id)) ? 'callout-warning bg-gradient-primary' : 'callout-info bg-gradient-grey' }} replied_message" data-replied="{{ $message->reply_message_id }}">
                                <p class="{{ (!empty($message->manager_id)) ? 'text-warning' : 'text-info' }} text-truncate m-0">{{ $message->replied_message->author }}</p>
                                <p class="text-truncate">
                                    @if (empty($message->replied_message->text) && !empty($message->replied_message->message_files) && count($message->replied_message->message_files))
                                        Прикреплены файлы
                                    @elseif (empty($message->replied_message->text) && !empty($message->replied_message->quiz_survey_id))
                                        Опрос
                                    @else
                                        {{ strip_tags($message->replied_message->text) }}
                                    @endif
                                </p>
                            </div>
                        @endif
                        @if (!empty($message->message_files) && count($message->message_files) && empty($message->deleted_at))
                            <div class="row">
                            @foreach ($message->message_files as $file)
                                @if ($file->type === "video/mp4")
                                    <div class="col-6 col-xs-6 col-sm-4 col-md-3 col-lg-2 message_image_block">
                                        <a id="{{ $file->id }}" data-fancybox="gallery" href="{{ url('storage/'.$file->src) }}" data-caption="{{ $message->text }}">
                                            <video class="message_image_item" src="{{ url('storage/'.$file->src) }}" autoplay loop muted playsinline width="{{ $file->width }}" height="{{ $file->height }}"></video>
                                        </a>
                                    </div>
                                @elseif ($file->type === "image")
                                    <div class="col-6 col-xs-6 col-sm-4 col-md-3 col-lg-2 message_image_block">
                                        <a id="{{ $file->id }}" data-fancybox="gallery" href="{{ url('storage/'.$file->src) }}" data-caption="{{ $message->text }}">
                                            <img class="message_image_item" src="{{ url('storage/'.$file->src) }}" alt="" width="{{ $file->width }}" height="{{ $file->height }}">
                                        </a>
                                    </div>
                                @elseif (!empty($file->preview))
                                    <div class="col-6 col-xs-6 col-sm-4 col-md-3 col-lg-2 message_image_block message_file_download_link" data-link="{{ url('storage/'.$file->src) }}" data-name="{{ $file->name }}">
                                        <img class="message_image_item" src="{{ url('storage/'.$file->preview) }}" alt="{{ $file->name }}" width="{{ $file->width }}" height="{{ $file->height }}">
                                    </div>
                                @else
                                    <div class="col-12 text-truncate">
                                        <span data-link="{{ url('storage/'.$file->src) }}" data-name="{{ $file->name }}" class="message_file_download_link">
                                            <i class="fa fa-paperclip mr-2" aria-hidden="true"></i>{{ $file->name }}
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                            </div>
                        @endif
                        <div class="chat_message_content">{!! $message->text_urlified !!}</div>
                        <div class="text-right">
                            <small class="text-muted">
                                {{ $message->author_mp }},
                                {{ $message->created_text }}
                                @if (!empty($message->functions))
                                    <span class="viewed_container" data-toggle="popover" data-viewed="{{ $message->popover }}"></span>
                                    <button type="button" class="btn bg-transparent py-0 pl-1 pr-0 functions" data-toggle="popover" data-content="{{ $message->functions }}">
                                        <i class="fa fa-cog" aria-hidden="true"></i>
                                    </button>
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div id="functions_popover"></div>
    @if (!empty($message->manager_id))
        <p class="text-right">{{ empty($message->viewed[$client->id]) ? 'Не прочитано заказчиком' : 'Прочитано заказчиком ' . $message->viewed[$client->id]['date'] }}</p>
    @endif
@else
    Пока сообщений нет
@endif
<div id="updateInfo" class="d-none" data-client_id="{{ $client->id }}" data-chat_updated_at="{{ $client->chat_updated_at_int }}"></div>
