import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import CardLoader from './CardLoader';
import InputGroup from './InputGroup';
import {URL_API_KEY} from '../util/uri';
import {fetch} from '../util/util';
import {
    Card,
    Form,
    Button,
    Table,
    Pagination,
} from 'react-bootstrap';
import trans from '../lang/index';

class PageApiKey extends Component {
    constructor(props) {
        super(props);
        this.state = {
            api_key: '',
            err_api_key: null,
            id_client: '',
            err_id_client: null,
            client_secret: '',
            err_client_secret: null,
            isLoading: false,
        }
    }
    updateState(k, v){
        const newState = this.state;
        newState[k] = v;
        this.setState(newState);
    }
    setError(k, clear){
      if(k === 'api_key'){
          if(clear){
              this.updateState('err_api_key', null);
          }else{
              this.updateState('err_api_key', trans.get('validation.required').replace(':attribute', trans.get('keyword.key')));
          }
      }else if(k === 'id_client'){
          if(clear){
              this.updateState('err_id_client', null);
          }else{
              this.updateState('err_id_client', trans.get('validation.required').replace(':attribute', trans.get('keyword.id_client')));
          }
      }else if(k === 'client_secret'){
          if(clear){
              this.updateState('err_client_secret', null);
          }else{
              this.updateState('err_client_secret', trans.get('validation.required').replace(':attribute', trans.get('keyword.client_secret')));
          }
      }
    }
    handlerChangeApiKey = (v) => {
        if(v === ''){
            this.setError('api_key', false);
        }
        else{
            this.updateState('api_key', v);
            this.setError('api_key', true);
        }

    }
    handlerChangeIdClient = (v) => {
        if(v === ''){
            this.setError('id_client', false);
        }
        else{
            this.updateState('id_client', v);
            this.setError('id_client', true);
        }
    }
    handlerChangeClientSecret = (v) => {
        if(v === ''){
            this.setError('client_secret', false);
        }
        else{
            this.updateState('client_secret', v);
            this.setError('client_secret', true);
        }
    }
    submitData = () => {
        console.log(this.state);
        if(this.state.api_key === ''){
            this.setError('api_key', false);
        }else if(this.state.id_client === ''){
            this.setError('id_client', false);
        }else if(this.state.client_secret === ''){
            this.setError('client_secret', false);
        }else{
            this.updateState('isLoading', true);
            fetch(URL_API_KEY, 'post', this.state)
                .then(result => {
                    setTimeout(() => {
                        this.updateState('isLoading', false);
                    }, 2000);
                })
                .catch(error => {
                    setTimeout(() => {
                        this.updateState('isLoading', false);
                    }, 2000);
                });
        }
    }
    render() {
        return (
            <Container>
                <div className="page-header">
                    <div className="page-title">
                        {trans.get('template.menu_api_key')}
                    </div>
                    <div className="page-subtitle">
                        1 - 12 of 1713 photos
                    </div>
                </div>
                <Row className="row-cards">
                    <Col lg="4">
                        <CardLoader isLoading={this.state.isLoading}>
                            <InputGroup
                                name="key"
                                type="text"
                                label={trans.get('keyword.key')}
                                placeholder={trans.get('keyword.enter') + ' ' + trans.get('keyword.key')}
                                value={this.state.api_key}
                                error={this.state.err_api_key}
                                onChange={this.handlerChangeApiKey}
                            />
                            <InputGroup
                                name="id_client"
                                type="text"
                                label={trans.get('keyword.id_client')}
                                placeholder={trans.get('keyword.enter') + ' ' + trans.get('keyword.id_client')}
                                value={this.state.id_client}
                                error={this.state.err_id_client}
                                onChange={this.handlerChangeIdClient}
                            />
                            <InputGroup
                                name="client_secret"
                                type="text"
                                label={trans.get('keyword.client_secret')}
                                placeholder={trans.get('keyword.enter') + ' ' + trans.get('keyword.client_secret')}
                                value={this.state.client_secret}
                                error={this.state.err_client_secret}
                                onChange={this.handlerChangeClientSecret}
                            />
                            <Button variant="primary"
                                    onClick={this.submitData}
                            >{trans.get('keyword.add')}</Button>
                        </CardLoader>

                    </Col>
                    <Col lg="8">
                        <Card>
                            <Table className="card-table table-vcenter">
                                <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>{trans.get('keyword.key')}</th>
                                    <th>{trans.get('keyword.id_client')}</th>
                                    <th>{trans.get('keyword.client_secret')}</th>
                                    <th className="w-1"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        1
                                    </td>
                                    <td>
                                        AIzaSyDz7l9VXfxfQSNzyTA-iwTiQ5sgT6wUKLL
                                    </td>
                                    <td>
                                        AIzaSyDz7l9VXfxfQSNzyTA-iwTiQ5sgT6wUKLL
                                    </td>
                                    <td>
                                        AIzaSyDz7l9VXfxfQSNzyTA-iwTiQ5sgT6wUKLL
                                    </td>
                                    <td className="text-center">
                                        <div className="item-action dropdown">
                                            <a href="javascript:void(0)" data-toggle="dropdown" className="icon">
                                                <i className="fe fe-more-vertical"></i>
                                            </a>
                                            <div className="dropdown-menu dropdown-menu-right">
                                                <a href="javascript:void(0)" className="dropdown-item"><i
                                                    className="dropdown-icon fe fe-edit-2"></i> {trans.get('keyword.edit')}
                                                </a>
                                                <a href="javascript:void(0)" className="dropdown-item"><i
                                                    className="dropdown-icon fe fe-delete"></i> {trans.get('keyword.delete')}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        2
                                    </td>
                                    <td>
                                        AIzaSyDz7l9VXfxfQSNzyTA-iwTiQ5sgT6wUKLL
                                    </td>
                                    <td>
                                        AIzaSyDz7l9VXfxfQSNzyTA-iwTiQ5sgT6wUKLL
                                    </td>
                                    <td>
                                        AIzaSyDz7l9VXfxfQSNzyTA-iwTiQ5sgT6wUKLL
                                    </td>
                                    <td className="text-center">
                                        <div className="item-action dropdown">
                                            <a href="javascript:void(0)" data-toggle="dropdown" className="icon">
                                                <i className="fe fe-more-vertical"></i>
                                            </a>
                                            <div className="dropdown-menu dropdown-menu-right">
                                                <a href="javascript:void(0)" className="dropdown-item"><i
                                                    className="dropdown-icon fe fe-edit-2"></i> {trans.get('keyword.edit')}
                                                </a>
                                                <a href="javascript:void(0)" className="dropdown-item"><i
                                                    className="dropdown-icon fe fe-delete"></i> {trans.get('keyword.delete')}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        3
                                    </td>
                                    <td>
                                        AIzaSyDz7l9VXfxfQSNzyTA-iwTiQ5sgT6wUKLL
                                    </td>
                                    <td>
                                        AIzaSyDz7l9VXfxfQSNzyTA-iwTiQ5sgT6wUKLL
                                    </td>
                                    <td>
                                        AIzaSyDz7l9VXfxfQSNzyTA-iwTiQ5sgT6wUKLL
                                    </td>
                                    <td className="text-center">
                                        <div className="item-action dropdown">
                                            <a href="javascript:void(0)" data-toggle="dropdown" className="icon">
                                                <i className="fe fe-more-vertical"></i>
                                            </a>
                                            <div className="dropdown-menu dropdown-menu-right">
                                                <a href="javascript:void(0)" className="dropdown-item"><i
                                                    className="dropdown-icon fe fe-edit-2"></i> {trans.get('keyword.edit')}
                                                </a>
                                                <a href="javascript:void(0)" className="dropdown-item"><i
                                                    className="dropdown-icon fe fe-delete"></i> {trans.get('keyword.delete')}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        4
                                    </td>
                                    <td>
                                        AIzaSyDz7l9VXfxfQSNzyTA-iwTiQ5sgT6wUKLL
                                    </td>
                                    <td>
                                        AIzaSyDz7l9VXfxfQSNzyTA-iwTiQ5sgT6wUKLL
                                    </td>
                                    <td>
                                        AIzaSyDz7l9VXfxfQSNzyTA-iwTiQ5sgT6wUKLL
                                    </td>
                                    <td className="text-center">
                                        <div className="item-action dropdown">
                                            <a href="javascript:void(0)" data-toggle="dropdown" className="icon">
                                                <i className="fe fe-more-vertical"></i>
                                            </a>
                                            <div className="dropdown-menu dropdown-menu-right">
                                                <a href="javascript:void(0)" className="dropdown-item"><i
                                                    className="dropdown-icon fe fe-edit-2"></i> {trans.get('keyword.edit')}
                                                </a>
                                                <a href="javascript:void(0)" className="dropdown-item"><i
                                                    className="dropdown-icon fe fe-delete"></i> {trans.get('keyword.delete')}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        5
                                    </td>
                                    <td>
                                        AIzaSyDz7l9VXfxfQSNzyTA-iwTiQ5sgT6wUKLL
                                    </td>
                                    <td>
                                        AIzaSyDz7l9VXfxfQSNzyTA-iwTiQ5sgT6wUKLL
                                    </td>
                                    <td>
                                        AIzaSyDz7l9VXfxfQSNzyTA-iwTiQ5sgT6wUKLL
                                    </td>
                                    <td className="text-center">
                                        <div className="item-action dropdown">
                                            <a href="javascript:void(0)" data-toggle="dropdown" className="icon">
                                                <i className="fe fe-more-vertical"></i>
                                            </a>
                                            <div className="dropdown-menu dropdown-menu-right">
                                                <a href="javascript:void(0)" className="dropdown-item"><i
                                                    className="dropdown-icon fe fe-edit-2"></i> {trans.get('keyword.edit')}
                                                </a>
                                                <a href="javascript:void(0)" className="dropdown-item"><i
                                                    className="dropdown-icon fe fe-delete"></i> {trans.get('keyword.delete')}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        6
                                    </td>
                                    <td>
                                        AIzaSyDz7l9VXfxfQSNzyTA-iwTiQ5sgT6wUKLL
                                    </td>
                                    <td>
                                        AIzaSyDz7l9VXfxfQSNzyTA-iwTiQ5sgT6wUKLL
                                    </td>
                                    <td>
                                        AIzaSyDz7l9VXfxfQSNzyTA-iwTiQ5sgT6wUKLL
                                    </td>
                                    <td className="text-center">
                                        <div className="item-action dropdown">
                                            <a href="javascript:void(0)" data-toggle="dropdown" className="icon">
                                                <i className="fe fe-more-vertical"></i>
                                            </a>
                                            <div className="dropdown-menu dropdown-menu-right">
                                                <a href="javascript:void(0)" className="dropdown-item"><i
                                                    className="dropdown-icon fe fe-edit-2"></i> {trans.get('keyword.edit')}
                                                </a>
                                                <a href="javascript:void(0)" className="dropdown-item"><i
                                                    className="dropdown-icon fe fe-delete"></i> {trans.get('keyword.delete')}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </Table>
                            <br/>
                            <Row className="justify-content-md-center">
                                <Pagination>
                                    <Pagination.First/>
                                    <Pagination.Prev/>
                                    <Pagination.Item>{1}</Pagination.Item>
                                    <Pagination.Ellipsis/>

                                    <Pagination.Item>{10}</Pagination.Item>
                                    <Pagination.Item>{11}</Pagination.Item>
                                    <Pagination.Item active>{12}</Pagination.Item>
                                    <Pagination.Item>{13}</Pagination.Item>
                                    <Pagination.Item disabled>{14}</Pagination.Item>

                                    <Pagination.Ellipsis/>
                                    <Pagination.Item>{20}</Pagination.Item>
                                    <Pagination.Next/>
                                    <Pagination.Last/>
                                </Pagination>
                            </Row>
                        </Card>
                    </Col>
                </Row>
            </Container>
        );
    }
}

if (document.getElementById('section-api-key')) {
    ReactDOM.render(<PageApiKey/>, document.getElementById('section-api-key'));
}