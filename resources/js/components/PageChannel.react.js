import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import {
    Container,
    Col,
    Row,
    Button,
    Form
} from 'react-bootstrap';
import _ from 'lodash';
import ReactTable from 'react-table';
import matchSorter from 'match-sorter'
import CardLoaderReact from './CardLoader.react';
import Pagination from './Pagination.react';
import trans from '../lang/index';
import {fetch, fetchOther} from '../util/util';
import {
    URL_CHANNEL_CALLBACK,
    URL_API_KEY_GET_PRIMARY,
    URL_CHANNEL_GET,
    URL_CHANNEL_CREATE,
    URL_CHANNEL_DELETE,
    URL_YOUTUBE_CHANNEL_LIST,
    STATUS_CODE_OK,
    TIME_OUT_REQUEST
} from '../util/constant';
import Icon from "./Icon.react";
import 'react-table/react-table.css'

class PageChannelReact extends Component {
    constructor(props) {
        super(props);
        this.state = {
            urlCallback: null,
            api_key: null,
            auth: null,
            channels: [],
            isLoading: false,
            deletes: [],
        }
    }

    updateState = (k, v) => {
        const newState = this.state;
        newState[k] = v;
        this.setState(newState);
    }

    componentWillMount() {
        this.init();
    }

    componentDidMount() {
        this.getData();
    }

    init = () => {
        fetch(URL_API_KEY_GET_PRIMARY, 'get', {})
            .then(result => {
                if (result.data.body.statusCode === STATUS_CODE_OK) {
                    if (result.data.body.data[0]) {
                        const api_key = result.data.body.data[0];
                        this.updateState('api_key', api_key);
                        this.updateState('urlCallback', URL_CHANNEL_CALLBACK + '?client_id=' + api_key.id_client);
                    }
                }
            })
            .catch(error => {
                console.log(error);
            });
        window.addEventListener("message", this.receiveMessage, false);
    }
    getData = () => {
        this.updateState('isLoading', true);
        fetch(URL_CHANNEL_GET, 'get', {})
            .then(result => {
                setTimeout(() => {
                    this.updateState('isLoading', false);
                    if (result.data.body.statusCode === STATUS_CODE_OK) {
                        this.updateState('channels', result.data.body.data);
                    }
                }, TIME_OUT_REQUEST);
            })
            .catch(error => {
                setTimeout(() => {
                    this.updateState('isLoading', false);
                    console.log(error);
                }, TIME_OUT_REQUEST);
            });
    }
    getChannels = (accessToken) => {
        fetchOther(URL_YOUTUBE_CHANNEL_LIST + '?part=snippet,statistics&mine=true', 'get', {}, {
            'Authorization': 'Bearer ' + accessToken,
            'Content-Type': 'application/json',
        }).then(result => {
            this.addChannel(result.data);
        }).catch(error => {
            console.log(error);
        });
    }
    resultAddChannel = (result) => {
        if (result.statusCode === STATUS_CODE_OK) {
            let newChannels = this.state.channels;
            let exits = false;
            newChannels.map((v, index) => {
               if(result.data.id === v.id){
                   v.title = result.data.title;
                   v.status = result.data.status;
                   v.view = result.data.view;
                   v.subscriber = result.data.subscriber;
                   exits = true;
               }
            });
            if(exits === false){
                newChannels.push(result.data);
            }
            this.updateState('channels', newChannels);
        }
    }
    addChannel = (data) => {
        if(data.items[0]){
            const auth = this.state.auth;
            const dataSend = {
                uid: data.items[0].id,
                title: data.items[0].snippet.title,
                thumbnail: data.items[0].snippet.thumbnails.default.url,
                view: data.items[0].statistics.viewCount,
                subscriber: data.items[0].statistics.subscriberCount,
                access_token: auth.access_token,
                refresh_token: auth.refresh_token,
                token_type: auth.token_type,
                expires_in: auth.expires_in,
                iat: auth.iat
            }
            fetch(URL_CHANNEL_CREATE, 'post', dataSend)
                .then(result => {
                    this.resultAddChannel(result.data.body);
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
    receiveMessage = event => {
        try {
            const auth = JSON.parse(event.data);
            if (auth.access_token && auth.refresh_token) {
                const currentDate = new Date();
                auth.iat = currentDate.getTime() + (auth.expires_in * 1000);
                this.updateState('auth', auth);
                this.getChannels(auth.access_token);
            }
        } catch (e) {

        }
    }
    handlerAddChannel = () => {
        if (this.state.urlCallback) {
            window.open(this.state.urlCallback, '_blank');
        } else {
            alert(trans.get('message.set_primary_api_key'));
        }
    }
    handlerDelete = () => {
        let newDeletes = this.state.deletes;
        if(newDeletes.length > 0){
            if (confirm(trans.get('message.confirm_delete'))) {
                this.updateState('isLoading', true);
                fetch(URL_CHANNEL_DELETE, 'delete', {items: newDeletes})
                    .then(result => {
                        setTimeout(() => {
                            this.updateState('isLoading', false);
                            if (result.data.body.statusCode === STATUS_CODE_OK) {
                                const newChannels = _.filter(this.state.channels, function (item) {
                                    return newDeletes.indexOf(item.id) === -1;
                                });
                                newDeletes = [];
                                this.updateState('deletes', newDeletes);
                                this.updateState('channels', newChannels);
                            }else{
                                alert(result.data.body.message);
                            }
                        }, TIME_OUT_REQUEST);
                    })
                    .catch(error => {
                        setTimeout(() => {
                            this.updateState('isLoading', false);
                            console.log(error);
                        }, TIME_OUT_REQUEST);
                    });
            }
        }else{
            alert(trans.get('message.no_choose'));
        }


    }
    handlerChangeDelete = (row, e) => {
        let newDeletes = this.state.deletes;
        if (e.target.checked) {
            if (newDeletes.indexOf(row.original.id) === -1) {
                newDeletes.push(row.original.id);
                this.updateState('deletes', newDeletes);
            }
        } else {
            let index = newDeletes.indexOf(row.original.id);
            if (index > -1) {
                newDeletes.splice(index, 1);
                this.updateState('deletes', newDeletes);
            }
        }
    }

    render() {
        const columns = [
            {
                Header: state => (
                    <Button variant="outline-primary" onClick={this.handlerDelete} size="sm"><Icon
                        name="fe fe-trash-2"/></Button>
                ),
                sortable: false,
                filterable: false,
                maxWidth: 55,
                className: 'd-flex justify-content-center',
                Cell: row => (
                    <label className="custom-control custom-checkbox custom-control-inline m-0">
                        <input type="checkbox"
                               className="custom-control-input"
                               name="delete"
                               checked={this.state.deletes.indexOf(row.original.id) > -1}
                               defaultChecked={this.state.deletes.indexOf(row.original.id) > -1}
                               onChange={(e) => this.handlerChangeDelete(row, e)}/>
                        <span className="custom-control-label"/>
                    </label>
                )
            },
            {
                Header: trans.get('keyword.name_channel'),
                id: "title",
                accessor: d => d.title,
                filterMethod: (filter, rows) =>
                    matchSorter(rows, filter.value, {keys: ["title"]}),
                filterAll: true

            },
            {
                Header: trans.get('keyword.view'),
                accessor: 'view',
                filterMethod: (filter, rows) =>
                    matchSorter(rows, filter.value, {keys: ["view"]}),
                filterAll: true
            },
            {
                Header: trans.get('keyword.subscriber'),
                accessor: 'subscriber',
                filterMethod: (filter, rows) =>
                    matchSorter(rows, filter.value, {keys: ["subscriber"]}),
                filterAll: true
            },
            {
                Header: trans.get('keyword.status'),
                accessor: 'status',
                id: "status",
                Cell: ({value}) => (value === 0 ? trans.get('keyword.normal') : trans.get('keyword.turn_off')),
                filterMethod: (filter, row) => {
                    if (filter.value === "all") {
                        return true;
                    }
                    if (filter.value === "false") {
                        return row.status === 0;
                    }
                    return row.status === 1;
                },
                Filter: ({filter, onChange}) =>
                    <Form.Control as="select"
                                  onChange={event => onChange(event.target.value)}
                                  value={filter ? filter.value : "all"}
                    >
                        <option value="all">{trans.get('keyword.show_all')}</option>
                        <option value="false">{trans.get('keyword.normal')}</option>
                        <option value="true">{trans.get('keyword.turn_off')}</option>
                    </Form.Control>
            },
        ];
        return (
            <Container>
                <div className="page-header">
                    <div className="page-title">
                        {trans.get('template.menu_sub_channels')}
                    </div>
                    <div className="page-options d-flex">
                        <Button
                            variant="secondary"
                            onClick={this.handlerAddChannel}>
                            {trans.get('keyword.add')}
                        </Button>
                    </div>
                </div>
                <Row className="row-cards">
                    <Col lg="12">
                        <CardLoaderReact isLoading={this.state.isLoading}>
                            <ReactTable
                                filterable
                                PaginationComponent={Pagination}
                                noDataText={trans.get('message.no_result')}
                                columns={columns}
                                data={this.state.channels}
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

if (document.getElementById('section-channel')) {
    ReactDOM.render(<PageChannelReact/>, document.getElementById('section-channel'));
}