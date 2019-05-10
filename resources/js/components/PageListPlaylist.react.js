import React from 'react';
import ReactDOM from "react-dom";
import ReactTable from "react-table";
import Pagination from "./Pagination.react";
import trans from "../lang";
import CardLoaderReact from "./CardLoader.react";
import Icon from "./Icon.react";
import {
    Col,
    Container,
    Form,
    Row,
    ButtonToolbar,
    Button,
    Alert,
    Modal
} from "react-bootstrap";
import matchSorter from 'match-sorter';
import 'react-table/react-table.css';
import {
    STATUS_CODE_OK,
    URL_PLAYLIST_GET,
    URL_PLAYLIST_DELETE,
    URL_PLAYLIST_UPDATE_VIDEO_STATUS,
    URL_PLAYLIST_UPDATE
} from '../util/constant';
import {
    fetch
} from '../util/util';
import InputGroupReact from "./InputGroup.react";
import TagsGroupReact from "./TagsGroup.react";
import TextareaGroupReact from "./TextareaGroup.react";

class PageListPlaylist extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            message: null,
            messageType: 'info',
            loadMore: false,
            playlists: [],
            showModal: false,
            keywordValue: [],
            keywordError: null,
            descriptionPlaylist: '',
            descriptionPlaylistError: null,
            titlePlaylist: '',
            titlePlaylistError: null,
            idLoading: false,
            playlistId: null,

        }
    }
    componentDidMount() {
        this.fetchData();
    }
    showAlert = (message, type) => {
        this.setState({
            message: message,
            messageType: type
        });
    }
    hideAlert = () => {
        this.showAlert(null, 'info');
    }
    handlerEdit = row => {
        let newKeywords = [];
        if(row.keywords.indexOf(' | ')){
            newKeywords = row.keywords.split(' | ');
        }else{
            newKeywords.push(row.keywords);
        }
        let newObjectKeywords = [];
        for(let i=0; i< newKeywords.length; i++){
            newObjectKeywords.push({
                id: newKeywords[i],
                text: newKeywords[i]
            });
        }
        this.setState({
            showModal: true,
            titlePlaylist: row.title,
            keywordValue: newObjectKeywords,
            descriptionPlaylist: row.description,
            playlistId: row.id
        });

    }

    handlerPause = row => {
        if(confirm(row.status_video !== 1 ? trans.get('message.confirm_play_playlist') : trans.get('message.confirm_pause_playlist'))){
            this.setState({loadMore : true});
            fetch(URL_PLAYLIST_UPDATE_VIDEO_STATUS, 'put', {
                playlist_id: row.id,
                status_video: row.status_video !== 1
            })
                .then(result => {
                    this.setState({loadMore: false});
                    if (result.data.body.statusCode === STATUS_CODE_OK) {
                        this.fetchData();
                        this.showAlert(trans.get('message.edit_success'), 'success');
                    }else{
                        this.showAlert(trans.get('message.edit_failed'), 'danger');
                    }
                })
                .catch(error => {
                    this.setState({loadMore: false});
                    console.log(error);
                })
        }
    }

    handlerDelete = row => {
        if(confirm(trans.get('message.confirm_delete'))){
            this.setState({loadMore : true});
            fetch(URL_PLAYLIST_DELETE, 'delete', {
                playlist_id: row.id,
            })
                .then(result => {
                    this.setState({loadMore: false});
                    if (result.data.body.statusCode === STATUS_CODE_OK) {
                        this.fetchData();
                        this.showAlert(trans.get('message.delete_success'), 'success');
                    }else{
                        this.showAlert(trans.get('message.delete_failed'), 'danger');
                    }
                })
                .catch(error => {
                    this.setState({loadMore: false});
                    console.log(error);
                })
        }
    }

    fetchData(){
        this.setState({loadMore: true});
        fetch(URL_PLAYLIST_GET, 'get', {})
            .then(result => {
                if (result.data.body.statusCode === STATUS_CODE_OK) {
                    this.setState({
                        loadMore: false,
                        playlists: result.data.body.data
                    });
                }
            })
            .catch(error => {
                this.setState({loadMore: false});
                console.log(error);
            })
    }

    setError(k, msg) {
        if (k === 'titlePlaylist') {
            this.setState({'titlePlaylistError': msg});
        } else if (k === 'keywordValue') {
            this.setState({'keywordError': msg});
        } else if (k === 'descriptionPlaylist') {
            this.setState({'descriptionPlaylistError': msg});
        }
    }

    handlerChangeKeyword = (newTags) => {
        if (newTags.length <= 0) {
            this.setError('keywordValue', trans.get('validation.required', {attribute: trans.get('message.label_keyword_playlist')}));
        } else {
            this.setState({
                keywordError: null,
                keywordValue: newTags
            })
        }
    }

    handlerChangeTitle = (e) => {
        if (e.target.value === '') {
            this.setError('titlePlaylist', trans.get('validation.required', {attribute: trans.get('message.label_title_playlist')}));
            this.setState({
                titlePlaylist: e.target.value
            });
        } else {
            this.setState({
                titlePlaylistError: null,
                titlePlaylist: e.target.value
            });
        }

    }

    handlerChangeDescription = (e) => {
        if (e.target.value === '') {
            this.setError('descriptionPlaylist', trans.get('validation.required', {attribute: trans.get('message.label_description_playlist')}));
            this.setState({
                descriptionPlaylist: e.target.value
            });
        } else {
            this.setState({
                descriptionPlaylistError: null,
                descriptionPlaylist: e.target.value
            });
        }
    }

    submitData = () => {
        const {titlePlaylist, descriptionPlaylist, keywordValue, playlistId}  = this.state;
        let isReq = true;
        if (keywordValue.length === 0) {
            isReq = false;
            this.setError('keywordValue', trans.get('validation.required', {attribute: trans.get('message.label_keyword_playlist')}));
        }
        if (titlePlaylist === '') {
            isReq = false;
            this.setError('titlePlaylist', trans.get('validation.required', {attribute: trans.get('message.label_title_playlist')}));
        }
        if(isReq){
            let newKeywords = [];
            keywordValue.map(val => {
                newKeywords.push(val.text);
            });
            let dataReq = {
                keywords: newKeywords,
                title: titlePlaylist,
                playlist_id: playlistId
            }
            if (descriptionPlaylist !== '') {
                dataReq.description = descriptionPlaylist;
            }
            this.setState({isLoading: true});
            fetch(URL_PLAYLIST_UPDATE, 'put', dataReq)
                .then(result => {
                    this.setState({isLoading: false});
                    if (result.data.body.statusCode === STATUS_CODE_OK) {
                        this.setState({
                            showModal: false,
                            titlePlaylist: '',
                            titlePlaylistError: null,
                            keywordValue: [],
                            keywordError: null,
                            descriptionPlaylist: '',
                            descriptionPlaylistError: null,
                            playlistId: null
                        });
                        this.fetchData();
                        this.showAlert(trans.get('message.edit_success'), 'success');
                    }else{
                        this.showAlert(trans.get('message.edit_failed'), 'danger');
                    }
                })
                .catch(error => {
                    this.setState({isLoading: false});
                    console.log(error);
                })
        }
    }

    render() {
        const {
            playlists,
            loadMore,
            message,
            messageType,
            showModal,
            keywordValue,
            keywordError,
            titlePlaylist,
            titlePlaylistError,
            descriptionPlaylist,
            descriptionPlaylistError,
        } = this.state;
        const columns = [
            {
                Header: trans.get('keyword.title'),
                accessor: 'title',
                minWidth: 380,
                filterMethod: (filter, rows) =>
                    matchSorter(rows, filter.value, {keys: ["title"]}),
                filterAll: true
            },
            {
                Header: trans.get('keyword.quantity_video'),
                accessor: 'video_count',
                maxWidth: 150,
                className: 'd-flex justify-content-center',
                filterMethod: (filter, rows) =>
                    matchSorter(rows, filter.value, {keys: ["video_count"]}),
                filterAll: true
            },
            {
                Header: trans.get('keyword.status'),
                accessor: 'status_video',
                maxWidth: 200,
                Cell: ({value}) => (value === 0 ? trans.get('keyword.un_process') : trans.get('keyword.processing')),
                filterMethod: (filter, row) => {
                    if (filter.value === "all") {
                        return true;
                    }
                    if (filter.value === "false") {
                        return row.status_video === 0;
                    }
                    return row.status_video === 1;
                },
                Filter: ({filter, onChange}) =>
                    <Form.Control as="select"
                                  onChange={event => onChange(event.target.value)}
                                  value={filter ? filter.value : "all"}
                    >
                        <option value="all">{trans.get('keyword.show_all')}</option>
                        <option value="false">{trans.get('keyword.un_process')}</option>
                        <option value="true">{trans.get('keyword.processing')}</option>
                    </Form.Control>
            },
            {
                Header: '',
                minWidth: 120,
                sortable: false,
                filterable: false,
                className: 'd-flex justify-content-center',
                Cell: row => (
                    <ButtonToolbar>
                        <Button onClick={ e => this.handlerEdit(row.original)} variant="primary" size="sm">
                            <Icon name='fe fe-edit-2'/>
                        </Button >
                        <Button onClick={ e => this.handlerPause(row.original)} variant="warning" size="sm" className="ml-1">
                            <Icon name={row.original.status_video ? 'fe fe-pause-circle' : 'fe fe-play-circle'}/>
                        </Button>
                        <Button onClick={ e => this.handlerDelete(row.original)} variant="danger" size="sm" className="ml-1">
                            <Icon name='fe fe-trash'/>
                        </Button>
                        <Button onClick={ e => window.open('https://www.youtube.com/playlist?list='+row.original.uid)} variant="info" size="sm" className="ml-1">
                            <Icon name='fe fe-link'/>
                        </Button>
                    </ButtonToolbar>
                )
            }
        ];
        return(
            <Container>
                <div className="page-header">
                    <div className="page-title">
                        {trans.get('template.menu_sub_playlist')}
                    </div>
                </div>
                <Row className="row-cards">
                    <Col lg="12">
                        <CardLoaderReact isLoading={loadMore}>
                            {message ?
                                <Alert onClose={this.hideAlert} dismissible variant={messageType}>
                                    <p>{message}</p></Alert> : ''}
                            <ReactTable
                                filterable
                                PaginationComponent={Pagination}
                                noDataText={trans.get('message.no_result')}
                                columns={columns}
                                data={playlists}
                                defaultPageSize={10}
                                className=""
                            />
                        </CardLoaderReact>
                    </Col>
                </Row>
                <Modal
                    size="lg"
                    show={showModal}
                    onHide={() => this.setState({
                        showModal: false,
                        titlePlaylist: '',
                        titlePlaylistError: null,
                        keywordValue: [],
                        keywordError: null,
                        descriptionPlaylist: '',
                        descriptionPlaylistError: null,
                        playlistId: null
                    })}
                    aria-labelledby="example-modal-sizes-title-lg"
                >
                    <Modal.Header closeButton>
                        <Modal.Title>
                            { trans.get('message.title_edit_playlist') }
                        </Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <CardLoaderReact isLoading={this.state.isLoading}>
                            <InputGroupReact
                                name='title'
                                label={trans.get('message.label_title_playlist')}
                                type="text"
                                onChange={this.handlerChangeTitle}
                                defaultValue={titlePlaylist}
                                value={titlePlaylist}
                                error={titlePlaylistError}
                            />
                            <TagsGroupReact
                                tags={keywordValue}
                                label={trans.get('message.label_keyword_playlist')}
                                onChange={this.handlerChangeKeyword}
                                error={keywordError}
                            />
                            <TextareaGroupReact
                                rows={5}
                                name="description"
                                title='description'
                                label={trans.get('message.label_description_playlist')}
                                onChange={this.handlerChangeDescription}
                                defaultValue={descriptionPlaylist}
                                value={descriptionPlaylist}
                                error={descriptionPlaylistError}
                            />
                            <Button variant="primary"
                                    onClick={this.submitData}
                            >{trans.get('keyword.edit')}</Button>
                        </CardLoaderReact>
                    </Modal.Body>
                </Modal>
            </Container>
        );
    }
}
if (document.getElementById('section-list-playlist')) {
    ReactDOM.render(<PageListPlaylist/>, document.getElementById('section-list-playlist'));
}