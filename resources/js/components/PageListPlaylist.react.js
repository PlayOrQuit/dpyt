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
    Button, Alert
} from "react-bootstrap";
import matchSorter from 'match-sorter';
import 'react-table/react-table.css';
import {
    STATUS_CODE_OK,
    URL_PLAYLIST_GET,
    URL_PLAYLIST_DELETE,
    URL_PLAYLIST_UPDATE_VIDEO_STATUS
} from '../util/constant';
import {
    fetch
} from '../util/util';

class PageListPlaylist extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            message: null,
            messageType: 'info',
            loadMore: false,
            playlists: [],
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
        console.log('------EDIT-------');
        console.log(row);
    }

    handlerPause = row => {
        if(confirm(row.status_video !== 1 ? trans.get('message.confirm_play_playlist') : trans.get('message.confirm_pause_playlist'))){
            console.log(row);
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
    render() {
        const { playlists, loadMore, message, messageType } = this.state;
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
            </Container>
        );
    }
}
if (document.getElementById('section-list-playlist')) {
    ReactDOM.render(<PageListPlaylist/>, document.getElementById('section-list-playlist'));
}